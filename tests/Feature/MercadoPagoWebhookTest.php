<?php

namespace Tests\Feature;

use App\Jobs\ProcessMercadoPagoNotification;
use App\Mail\SubscriptionActivatedMail;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

/**
 * El webhook es la única vía por la que se enteran los pagos que no se resuelven en el
 * acto (PSE, efectivo, `in_process`) y los que cambian después (reembolso, contracargo).
 * Es la pieza con más consecuencias del flujo y la que no tenía ni un test.
 */
class MercadoPagoWebhookTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        Plan::query()->delete();

        $this->plan = Plan::create([
            'name'           => 'presencia-digital',
            'display_name'   => 'Presencia Digital',
            'price_regular'  => 69000,
            'offer_price'    => 39000,
            'billing_period' => 'yearly',
            'max_cards'      => 1,
            'is_active'      => true,
            'is_default'     => true,
        ]);

        $this->company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $owner = User::create([
            'company_id' => $this->company->id,
            'name'       => 'Dueño',
            'email'      => 'duenio@test.com',
            'password'   => bcrypt('secret123'),
        ]);
        $this->company->update(['user_id' => $owner->id]);
    }

    private function pendingPayment(array $overrides = []): Payment
    {
        return Payment::create(array_merge([
            'company_id' => $this->company->id,
            'amount'     => 39000,
            'currency'   => 'COP',
            'status'     => 'pending',
            'metadata'   => ['plan_id' => $this->plan->id],
        ], $overrides));
    }

    /**
     * Firma válida según el esquema de MercadoPago:
     * template `id:{dataId};request-id:{requestId};ts:{ts};` con HMAC-SHA256.
     */
    private function signedHeaders(string $dataId, string $secret = 'test-secret'): array
    {
        $ts = '1700000000';
        $requestId = 'req-abc';
        $hash = hash_hmac('sha256', "id:{$dataId};request-id:{$requestId};ts:{$ts};", $secret);

        return [
            'x-signature'  => "ts={$ts},v1={$hash}",
            'x-request-id' => $requestId,
        ];
    }

    // ---------- Firma ----------

    public function test_rechaza_una_firma_invalida(): void
    {
        config(['mercadopago.webhook_secret' => 'test-secret']);
        Bus::fake();

        $this->postJson('/api/mercadopago/webhook', [
            'type' => 'payment',
            'data' => ['id' => '123'],
        ], [
            'x-signature'  => 'ts=1700000000,v1=firma-falsa',
            'x-request-id' => 'req-abc',
        ])->assertStatus(400);

        Bus::assertNothingDispatched();
    }

    public function test_rechaza_si_falta_el_header_de_firma(): void
    {
        config(['mercadopago.webhook_secret' => 'test-secret']);
        Bus::fake();

        $this->postJson('/api/mercadopago/webhook', [
            'type' => 'payment',
            'data' => ['id' => '123'],
        ])->assertStatus(400);

        Bus::assertNothingDispatched();
    }

    public function test_acepta_una_firma_valida_y_despacha_el_job(): void
    {
        config(['mercadopago.webhook_secret' => 'test-secret']);
        Bus::fake();

        $this->postJson('/api/mercadopago/webhook', [
            'type' => 'payment',
            'data' => ['id' => '123'],
        ], $this->signedHeaders('123'))->assertOk();

        Bus::assertDispatched(ProcessMercadoPagoNotification::class);
    }

    /**
     * Sin secreto configurado la validación se salta entera. Es cómodo en local y un
     * agujero en producción: cualquiera podría activar suscripciones a mano.
     */
    public function test_sin_secreto_configurado_acepta_cualquier_firma(): void
    {
        config(['mercadopago.webhook_secret' => null]);
        Bus::fake();

        $this->postJson('/api/mercadopago/webhook', [
            'type' => 'payment',
            'data' => ['id' => '123'],
        ])->assertOk();

        Bus::assertDispatched(ProcessMercadoPagoNotification::class);
    }

    public function test_ignora_notificaciones_que_no_son_de_pago(): void
    {
        config(['mercadopago.webhook_secret' => null]);
        Bus::fake();

        $this->postJson('/api/mercadopago/webhook', [
            'type' => 'plan',
            'data' => ['id' => '123'],
        ])->assertOk();

        Bus::assertNothingDispatched();
    }

    // ---------- Procesamiento del job ----------

    private function mockGateway(array $mpPayment): void
    {
        $mock = Mockery::mock(MercadoPagoService::class)->makePartial();
        $mock->shouldReceive('getPayment')->andReturn($mpPayment);
        $this->app->instance(MercadoPagoService::class, $mock);
    }

    private function mpPayment(string $status, array $overrides = []): array
    {
        return array_merge([
            'id'                => 'MP-1',
            'status'            => $status,
            'status_detail'     => 'accredited',
            'payment_method_id' => 'visa',
            'payment_type_id'   => 'credit_card',
            'order_id'          => null,
            'external_reference' => null,
            'metadata'          => [],
            'date_approved'     => now()->toIso8601String(),
        ], $overrides);
    }

    public function test_un_pago_aprobado_activa_la_suscripcion(): void
    {
        Mail::fake();
        $payment = $this->pendingPayment();
        $this->mockGateway($this->mpPayment('approved', [
            'metadata' => ['payment_id' => $payment->id],
        ]));

        (new ProcessMercadoPagoNotification('MP-1'))->handle(app(\App\Services\SubscriptionService::class));

        $payment->refresh();
        $this->assertSame('approved', $payment->status);
        $this->assertNotNull($payment->subscription_id);

        $subscription = $this->company->fresh()->activeSubscription();
        $this->assertSame('active', $subscription->status);
        Mail::assertQueued(SubscriptionActivatedMail::class);
    }

    public function test_un_pago_rechazado_no_activa_nada(): void
    {
        Mail::fake();
        $payment = $this->pendingPayment();
        $this->mockGateway($this->mpPayment('rejected', [
            'status_detail' => 'cc_rejected_insufficient_amount',
            'metadata'      => ['payment_id' => $payment->id],
        ]));

        (new ProcessMercadoPagoNotification('MP-1'))->handle(app(\App\Services\SubscriptionService::class));

        $payment->refresh();
        $this->assertSame('declined', $payment->status);
        $this->assertNull($payment->paid_at);
        $this->assertNull($this->company->fresh()->activeSubscription());
        Mail::assertNothingQueued();
    }

    /**
     * MercadoPago reenvía la misma notificación varias veces. Procesarla dos veces no
     * puede regalar dos periodos ni mandar dos correos.
     */
    public function test_la_misma_notificacion_dos_veces_no_duplica_la_suscripcion(): void
    {
        Mail::fake();
        $payment = $this->pendingPayment();
        $this->mockGateway($this->mpPayment('approved', [
            'metadata' => ['payment_id' => $payment->id],
        ]));

        $service = app(\App\Services\SubscriptionService::class);
        (new ProcessMercadoPagoNotification('MP-1'))->handle($service);
        (new ProcessMercadoPagoNotification('MP-1'))->handle($service);

        $this->assertSame(1, Subscription::where('company_id', $this->company->id)->count());
        Mail::assertQueuedCount(1);
    }

    /**
     * Un contracargo o reembolso posterior a la aprobación tiene que reflejarse: si no,
     * el cliente conserva un año de servicio por un pago que ya no existe.
     */
    public function test_un_reembolso_posterior_actualiza_el_pago(): void
    {
        Mail::fake();
        $payment = $this->pendingPayment();
        $service = app(\App\Services\SubscriptionService::class);

        // Primero se aprueba.
        $this->mockGateway($this->mpPayment('approved', [
            'metadata' => ['payment_id' => $payment->id],
        ]));
        (new ProcessMercadoPagoNotification('MP-1'))->handle($service);

        // Después MercadoPago avisa del reembolso.
        $this->mockGateway($this->mpPayment('refunded', [
            'metadata' => ['payment_id' => $payment->id],
        ]));
        (new ProcessMercadoPagoNotification('MP-1'))->handle($service);

        $this->assertSame('refunded', $payment->fresh()->status);
    }

    /**
     * Un reembolso degrada la suscripcion a `past_due` en vez de cancelarla: la tarjeta
     * sigue publicada durante los dias de gracia, el cliente ve el aviso de renovar, y si
     * nadie hace nada el comando diario la expira sola. Cancelar de golpe tumbaria a un
     * cliente cuya disputa quiza se resuelva a favor del comercio.
     */
    public function test_un_reembolso_degrada_la_suscripcion_a_past_due(): void
    {
        Mail::fake();
        $payment = $this->pendingPayment();
        $service = app(\App\Services\SubscriptionService::class);

        $this->mockGateway($this->mpPayment('approved', [
            'metadata' => ['payment_id' => $payment->id],
        ]));
        (new ProcessMercadoPagoNotification('MP-1'))->handle($service);

        $this->mockGateway($this->mpPayment('refunded', [
            'metadata' => ['payment_id' => $payment->id],
        ]));
        (new ProcessMercadoPagoNotification('MP-1'))->handle($service);

        $company = $this->company->fresh();

        $this->assertSame('past_due', $company->latestSubscription()->status);
        $this->assertTrue($company->hasPublicAccess());
    }

    /**
     * Si el cliente ya volvio a pagar despues, el reembolso del cobro viejo no puede
     * tumbar la suscripcion nueva.
     */
    public function test_un_reembolso_no_toca_una_suscripcion_ya_renovada(): void
    {
        Mail::fake();
        $viejo   = $this->pendingPayment();
        $service = app(\App\Services\SubscriptionService::class);

        $this->mockGateway($this->mpPayment('approved', [
            'metadata' => ['payment_id' => $viejo->id],
        ]));
        (new ProcessMercadoPagoNotification('MP-1'))->handle($service);

        // El cliente renueva: la suscripcion del pago viejo queda cancelada y nace otra.
        $nueva = $service->activateSubscription($this->company->fresh(), $this->plan, 'mercadopago');

        $this->mockGateway($this->mpPayment('refunded', [
            'metadata' => ['payment_id' => $viejo->id],
        ]));
        (new ProcessMercadoPagoNotification('MP-1'))->handle($service);

        $this->assertSame('active', $nueva->fresh()->status);
        $this->assertTrue($this->company->fresh()->hasPublicAccess());
    }

    public function test_un_pago_local_inexistente_no_revienta(): void
    {
        $this->mockGateway($this->mpPayment('approved'));

        (new ProcessMercadoPagoNotification('MP-DESCONOCIDO'))
            ->handle(app(\App\Services\SubscriptionService::class));

        $this->assertSame(0, Subscription::count());
    }

    /**
     * Ruta de respaldo cuando el pago local aún no tiene guardado el id de MercadoPago:
     * se localiza por `external_reference` (NEXOS-{id}-{timestamp}).
     */
    public function test_encuentra_el_pago_local_por_external_reference(): void
    {
        Mail::fake();
        $payment = $this->pendingPayment();
        $this->mockGateway($this->mpPayment('approved', [
            'external_reference' => 'NEXOS-' . $payment->id . '-1700000000',
            'metadata'           => [],
        ]));

        (new ProcessMercadoPagoNotification('MP-1'))->handle(app(\App\Services\SubscriptionService::class));

        $this->assertSame('approved', $payment->fresh()->status);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
