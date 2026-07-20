<?php

namespace Tests\Feature;

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
 * El recordatorio avisaba a 7 días exactos, lo que con un trial de 7 días disparaba el
 * mismo día del alta. Ahora la cadencia es configurable y no se repite.
 */
class TrialReminderTest extends TestCase
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

    private function trialEndingIn(int $days, string $slug): Subscription
    {
        $company = Company::create(['name' => 'Acme ' . $slug, 'slug' => $slug]);

        $owner = User::create([
            'company_id' => $company->id,
            'name'       => 'Dueño',
            'email'      => $slug . '@test.com',
            'password'   => bcrypt('secret123'),
        ]);
        $company->update(['user_id' => $owner->id]);

        return Subscription::create([
            'company_id'           => $company->id,
            'plan_id'              => $this->plan->id,
            'status'               => 'trial',
            'trial_ends_at'        => now()->addDays($days),
            'current_period_start' => now(),
            'current_period_end'   => now()->addDays($days),
        ]);
    }

    public function test_el_trial_por_defecto_es_de_7_dias(): void
    {
        $this->assertSame(7, AppSetting::getTrialDays());
    }

    public function test_los_dias_restantes_no_se_sub_reportan(): void
    {
        // Un vencimiento a 2 dias exactos son 1.9999 dias: truncar daba 1.
        $this->assertSame(2, $this->trialEndingIn(2, 'dos-dias')->daysRemaining());
        $this->assertSame(7, $this->trialEndingIn(7, 'siete-dias')->daysRemaining());
    }

    public function test_en_trial_los_dias_se_cuentan_sobre_trial_ends_at(): void
    {
        $subscription = $this->trialEndingIn(3, 'cuenta-trial');

        // current_period_end desalineado (p. ej. tras extender el trial a mano):
        // el conteo debe seguir la fecha del trial, que es la que usan los correos.
        $subscription->update(['current_period_end' => now()->subDay()]);

        $this->assertSame(3, $subscription->fresh()->daysRemaining());
    }

    public function test_la_cadencia_por_defecto_es_3_y_1(): void
    {
        $this->assertSame([3, 1], AppSetting::getTrialReminderDays());
    }

    public function test_avisa_a_los_3_y_a_1_dia(): void
    {
        Mail::fake();

        $this->trialEndingIn(3, 'faltan-tres');
        $this->trialEndingIn(1, 'falta-uno');
        $this->trialEndingIn(5, 'faltan-cinco'); // fuera de la cadencia

        $this->artisan('subscriptions:check-expiry')->assertSuccessful();

        Mail::assertQueued(TrialExpiringMail::class, 2);
        Mail::assertQueued(
            TrialExpiringMail::class,
            fn ($mail) => $mail->hasTo('faltan-tres@test.com')
        );
        Mail::assertQueued(
            TrialExpiringMail::class,
            fn ($mail) => $mail->hasTo('falta-uno@test.com')
        );
    }

    public function test_no_reenvia_el_mismo_recordatorio_si_el_comando_corre_dos_veces(): void
    {
        Mail::fake();

        $this->trialEndingIn(3, 'faltan-tres');

        $this->artisan('subscriptions:check-expiry')->assertSuccessful();
        $this->artisan('subscriptions:check-expiry')->assertSuccessful();

        Mail::assertQueued(TrialExpiringMail::class, 1);
    }

    public function test_la_cadencia_es_configurable(): void
    {
        Mail::fake();
        AppSetting::set('trial_reminder_days', '5');

        $this->trialEndingIn(5, 'faltan-cinco');
        $this->trialEndingIn(3, 'faltan-tres');

        $this->artisan('subscriptions:check-expiry')->assertSuccessful();

        Mail::assertQueued(TrialExpiringMail::class, 1);
        Mail::assertQueued(
            TrialExpiringMail::class,
            fn ($mail) => $mail->hasTo('faltan-cinco@test.com')
        );
    }

    public function test_si_se_extiende_el_trial_vuelve_a_avisarse(): void
    {
        Mail::fake();

        $subscription = $this->trialEndingIn(3, 'extendido');
        $this->artisan('subscriptions:check-expiry')->assertSuccessful();
        Mail::assertQueued(TrialExpiringMail::class, 1);

        // Se extiende el trial una semana: la marca del ciclo anterior no debe silenciar
        // el aviso del nuevo vencimiento, porque la clave incluye la fecha.
        $subscription->update(['trial_ends_at' => now()->addDays(10)]);
        $this->assertFalse($subscription->fresh()->wasReminderSent(3));

        // Al acercarse de nuevo a 3 días del NUEVO vencimiento, vuelve a avisar.
        $this->travel(7)->days();
        $this->artisan('subscriptions:check-expiry')->assertSuccessful();

        Mail::assertQueued(TrialExpiringMail::class, 2);
    }
}
