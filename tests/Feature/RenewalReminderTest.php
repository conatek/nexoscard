<?php

namespace Tests\Feature;

use App\Mail\SubscriptionExpiringMail;
use App\Mail\TrialExpiringMail;
use App\Models\AppSetting;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Avisos de renovación para quien ya paga.
 *
 * El plan es anual y no se renueva solo, así que sin estos correos el cliente descubre el
 * vencimiento cuando su tarjeta ya está fuera de línea.
 */
class RenewalReminderTest extends TestCase
{
    use RefreshDatabase;

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
    }

    private function paidSubscription(int $daysToExpiry, string $status = 'active'): Subscription
    {
        static $n = 0;
        $n++;

        $company = Company::create(['name' => "Empresa {$n}", 'slug' => "empresa-{$n}"]);
        $owner   = User::create([
            'company_id' => $company->id,
            'name'       => 'Dueño',
            'email'      => "duenio{$n}@test.com",
            'password'   => bcrypt('secret123'),
        ]);
        $company->update(['user_id' => $owner->id]);

        return Subscription::create([
            'company_id'           => $company->id,
            'plan_id'              => $this->plan->id,
            'billing_period'       => 'yearly',
            'status'               => $status,
            'payment_method'       => 'mercadopago',
            'current_period_start' => now()->subYear()->addDays($daysToExpiry),
            'current_period_end'   => now()->addDays($daysToExpiry),
        ]);
    }

    private function runCommand(): void
    {
        $this->artisan('subscriptions:check-expiry')->assertSuccessful();
    }

    public function test_la_cadencia_por_defecto_es_15_y_3(): void
    {
        $this->assertSame([15, 3], AppSetting::getRenewalReminderDays());
    }

    public function test_avisa_a_los_15_y_a_los_3_dias(): void
    {
        Mail::fake();

        $this->paidSubscription(15);
        $this->paidSubscription(3);
        $this->paidSubscription(9); // Ni uno ni otro: no toca aviso.

        $this->runCommand();

        Mail::assertQueued(SubscriptionExpiringMail::class, 2);
    }

    public function test_no_avisa_a_una_suscripcion_en_prueba(): void
    {
        Mail::fake();

        // El trial tiene su propia cadencia y su propio correo; duplicar el aviso seria
        // mandarle dos cosas distintas el mismo dia.
        $sub = $this->paidSubscription(3, 'trial');
        $sub->update(['trial_ends_at' => now()->addDays(3)]);

        $this->runCommand();

        Mail::assertNotQueued(SubscriptionExpiringMail::class);
        Mail::assertQueued(TrialExpiringMail::class);
    }

    public function test_no_reenvia_el_mismo_aviso_si_el_comando_corre_dos_veces(): void
    {
        Mail::fake();

        $this->paidSubscription(15);

        $this->runCommand();
        $this->runCommand();

        Mail::assertQueued(SubscriptionExpiringMail::class, 1);
    }

    /**
     * La trampa del dedupe: la clave del aviso de renovación tiene que anclarse a
     * `current_period_end`, no a `trial_ends_at` (que en una suscripción pagada es null).
     * Con el ancla equivocada todos los avisos comparten la clave "sin-fecha" y solo se
     * envía el primero, para siempre.
     */
    public function test_al_renovar_vuelve_a_avisarse_en_el_ciclo_nuevo(): void
    {
        Mail::fake();

        $sub = $this->paidSubscription(15);
        $this->runCommand();
        Mail::assertQueued(SubscriptionExpiringMail::class, 1);

        // Renueva: periodo nuevo, y dentro de un año vuelve a faltar 15 días.
        $sub->update([
            'current_period_start' => now(),
            'current_period_end'   => now()->addYear(),
        ]);
        $this->travel(350)->days();

        $this->runCommand();

        Mail::assertQueued(SubscriptionExpiringMail::class, 2);
    }

    /**
     * Los avisos de prueba y los de renovación no pueden pisarse: son claves distintas
     * aunque coincida el número de días.
     */
    public function test_el_aviso_de_prueba_no_bloquea_el_de_renovacion(): void
    {
        Mail::fake();

        $sub = $this->paidSubscription(3, 'trial');
        $sub->update(['trial_ends_at' => now()->addDays(3)]);

        $this->runCommand();
        Mail::assertQueued(TrialExpiringMail::class, 1);

        // La misma suscripción pasa a pagada, con vencimiento también a 3 días.
        $sub->update(['status' => 'active', 'current_period_end' => now()->addDays(3)]);

        $this->runCommand();

        Mail::assertQueued(SubscriptionExpiringMail::class, 1);
    }

    public function test_la_cadencia_es_configurable(): void
    {
        Mail::fake();
        AppSetting::set('renewal_reminder_days', '30');

        $this->paidSubscription(30);
        $this->paidSubscription(15);

        $this->runCommand();

        Mail::assertQueued(SubscriptionExpiringMail::class, 1);
    }

    public function test_el_correo_lleva_los_datos_de_la_renovacion(): void
    {
        Mail::fake();
        AppSetting::set('grace_period_days', 10);

        $sub = $this->paidSubscription(15);

        $this->runCommand();

        Mail::assertQueued(SubscriptionExpiringMail::class, function ($mail) use ($sub) {
            return $mail->daysRemaining === 15
                && $mail->graceDays === 10
                && $mail->plan?->id === $this->plan->id
                && $mail->periodEndsAt->isSameDay($sub->current_period_end);
        });
    }

    public function test_una_suscripcion_sin_dueno_no_rompe_el_comando(): void
    {
        Mail::fake();

        $sub = $this->paidSubscription(15);
        $sub->company->update(['user_id' => null]);

        $this->runCommand();

        Mail::assertNotQueued(SubscriptionExpiringMail::class);
    }
}
