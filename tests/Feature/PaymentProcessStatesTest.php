<?php

namespace Tests\Feature;

use App\Mail\SubscriptionActivatedMail;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

/**
 * `POST /api/payments/process` frente a cada respuesta posible del gateway. Solo un pago
 * aprobado puede activar el plan; cualquier otro estado tiene que dejar la empresa como
 * estaba y no mandar el correo de bienvenida.
 */
class PaymentProcessStatesTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $user;
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
        $this->user    = User::create([
            'company_id' => $this->company->id,
            'name'       => 'Dueño',
            'email'      => 'duenio@test.com',
            'password'   => bcrypt('secret123'),
        ]);
        $this->company->update(['user_id' => $this->user->id]);
    }

    private function mockGateway(array $response): MercadoPagoService
    {
        $mock = Mockery::mock(MercadoPagoService::class)->makePartial();
        $mock->shouldReceive('createPayment')->andReturn($response);
        $this->app->instance(MercadoPagoService::class, $mock);

        return $mock;
    }

    private function gatewayResponse(string $status, array $overrides = []): array
    {
        return array_merge([
            'id'                => 'MP-1',
            'status'            => $status,
            'status_detail'     => 'accredited',
            'payment_method_id' => 'visa',
            'payment_type_id'   => 'credit_card',
            'date_approved'     => now()->toIso8601String(),
        ], $overrides);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'plan_id'           => $this->plan->id,
            'token'             => 'fake-card-token',
            'payment_method_id' => 'visa',
            'installments'      => 1,
            'payer_email'       => 'duenio@test.com',
        ], $overrides);
    }

    private function pay(array $overrides = [])
    {
        return $this->actingAs($this->user)
            ->postJson('/api/payments/process', $this->payload($overrides));
    }

    // ---------- Estados del gateway ----------

    public function test_aprobado_activa_el_plan_y_manda_el_correo(): void
    {
        Mail::fake();
        $this->mockGateway($this->gatewayResponse('approved'));

        $this->pay()->assertOk()->assertJsonPath('status', 'approved');

        $payment = Payment::first();
        $this->assertSame('approved', $payment->status);
        $this->assertNotNull($payment->paid_at);
        $this->assertNotNull($payment->subscription_id);

        $this->assertSame('active', $this->company->fresh()->activeSubscription()->status);
        Mail::assertQueued(SubscriptionActivatedMail::class);
    }

    /**
     * PSE y efectivo caen aquí: el pago queda abierto y lo resuelve el webhook. Nada
     * puede activarse todavía.
     */
    public function test_pendiente_no_activa_nada(): void
    {
        Mail::fake();
        $this->mockGateway($this->gatewayResponse('in_process', [
            'status_detail' => 'pending_contingency',
            'date_approved' => null,
        ]));

        $this->pay()->assertOk()->assertJsonPath('status', 'pending');

        $payment = Payment::first();
        $this->assertSame('pending', $payment->status);
        $this->assertNull($payment->paid_at);
        $this->assertNull($payment->subscription_id);

        $this->assertNull($this->company->fresh()->activeSubscription());
        Mail::assertNothingQueued();
    }

    public function test_rechazado_no_activa_nada(): void
    {
        Mail::fake();
        $this->mockGateway($this->gatewayResponse('rejected', [
            'status_detail' => 'cc_rejected_insufficient_amount',
            'date_approved' => null,
        ]));

        $this->pay()->assertOk()->assertJsonPath('status', 'declined');

        $this->assertSame('declined', Payment::first()->status);
        $this->assertNull($this->company->fresh()->activeSubscription());
        Mail::assertNothingQueued();
    }

    /**
     * Cuando la API de MercadoPago falla, el servicio devuelve id null. El pago local
     * tiene que quedar como rechazado, no colgado en pendiente para siempre.
     */
    public function test_un_error_del_gateway_deja_el_pago_rechazado(): void
    {
        Mail::fake();
        $this->mockGateway([
            'id'            => null,
            'status'        => 'error',
            'status_detail' => 'Error de pago',
        ]);

        $this->pay()->assertOk()->assertJsonPath('status', 'declined');

        $this->assertSame('declined', Payment::first()->status);
        $this->assertNull($this->company->fresh()->activeSubscription());
        Mail::assertNothingQueued();
    }

    // ---------- Enlace con el webhook ----------

    /**
     * El webhook localiza el pago local por `mercadopago_payment_id`, por
     * `external_reference` o por `metadata.payment_id`. Si al crear el pago en
     * MercadoPago no se manda ninguna de las dos últimas, el único vínculo es un id que
     * se guarda *después* de que la API responde: si la notificación llega antes, o esa
     * escritura falla, el pago queda huérfano y la suscripción nunca se activa.
     */
    public function test_el_pago_enviado_a_mercadopago_lleva_referencia_al_pago_local(): void
    {
        $capturado = null;

        $mock = Mockery::mock(MercadoPagoService::class)->makePartial();
        $mock->shouldReceive('createPayment')
            ->andReturnUsing(function (array $data) use (&$capturado) {
                $capturado = $data;
                return $this->gatewayResponse('approved');
            });
        $this->app->instance(MercadoPagoService::class, $mock);

        Mail::fake();
        $this->pay()->assertOk();

        $payment = Payment::first();

        $this->assertArrayHasKey('external_reference', $capturado);
        $this->assertStringContainsString((string) $payment->id, $capturado['external_reference']);
        $this->assertSame($payment->id, $capturado['metadata']['payment_id'] ?? null);
    }

    // ---------- Guardas de acceso ----------

    public function test_un_usuario_sin_sesion_no_puede_pagar(): void
    {
        $this->postJson('/api/payments/process', $this->payload())
            ->assertStatus(401);

        $this->assertSame(0, Payment::count());
    }

    public function test_un_plan_inactivo_no_se_puede_pagar(): void
    {
        $this->plan->update(['is_active' => false]);

        $this->pay()->assertStatus(404);
        $this->assertSame(0, Payment::count());
    }

    public function test_un_usuario_sin_empresa_no_puede_pagar(): void
    {
        $huerfano = User::create([
            'name'     => 'Sin empresa',
            'email'    => 'sinempresa@test.com',
            'password' => bcrypt('secret123'),
        ]);

        $this->actingAs($huerfano)
            ->postJson('/api/payments/process', $this->payload())
            ->assertStatus(422);

        $this->assertSame(0, Payment::count());
    }

    /**
     * Renovar estando vencido tiene que devolver una suscripción vigente, no sumarse a la
     * anterior. La vieja queda cerrada.
     */
    public function test_renovar_estando_vencido_deja_una_sola_suscripcion_vigente(): void
    {
        Mail::fake();

        Subscription::create([
            'company_id'           => $this->company->id,
            'plan_id'              => $this->plan->id,
            'billing_period'       => 'yearly',
            'status'               => 'expired',
            'current_period_start' => now()->subYear(),
            'current_period_end'   => now()->subDay(),
        ]);

        $this->mockGateway($this->gatewayResponse('approved'));
        $this->pay()->assertOk();

        $company = $this->company->fresh();

        $this->assertSame('active', $company->latestSubscription()->status);
        $this->assertTrue($company->hasPublicAccess());
        $this->assertSame(
            1,
            Subscription::where('company_id', $company->id)->whereIn('status', ['trial', 'active'])->count()
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
