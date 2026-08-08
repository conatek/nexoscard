<?php

namespace Tests\Feature;

use App\Mail\SubscriptionExpiredMail;
use App\Mail\TrialExpiredMail;
use App\Models\AppSetting;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * La máquina de estados que corre `subscriptions:check-expiry` a diario. De ella depende
 * cuándo se cae una tarjeta pública y cuándo se avisa al cliente, así que cada transición
 * tiene que ser exacta en sus bordes: un día de más o de menos es servicio regalado o un
 * cliente cortado antes de tiempo.
 *
 *   trial    --vence trial_ends_at-->            expired
 *   active   --vence current_period_end-->       past_due
 *   past_due --pasan los días de gracia-->       expired
 */
class SubscriptionLifecycleTest extends TestCase
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
            'billing_period' => 'yearly',
            'max_cards'      => 1,
            'is_active'      => true,
            'is_default'     => true,
        ]);
    }

    private function companyWithSubscription(string $status, array $overrides = []): Subscription
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

        return Subscription::create(array_merge([
            'company_id'           => $company->id,
            'plan_id'              => $this->plan->id,
            'billing_period'       => 'yearly',
            'status'               => $status,
            'current_period_start' => now()->subYear(),
            'current_period_end'   => now()->addYear(),
        ], $overrides));
    }

    private function runCommand(): void
    {
        $this->artisan('subscriptions:check-expiry')->assertSuccessful();
    }

    // ---------- trial -> expired ----------

    public function test_un_trial_vencido_pasa_a_expirado_y_avisa(): void
    {
        Mail::fake();

        $sub = $this->companyWithSubscription('trial', [
            'trial_ends_at'      => now()->subHour(),
            'current_period_end' => now()->subHour(),
        ]);

        $this->runCommand();

        $this->assertSame('expired', $sub->fresh()->status);
        Mail::assertQueued(TrialExpiredMail::class);
    }

    public function test_un_trial_vigente_no_se_toca(): void
    {
        Mail::fake();

        $sub = $this->companyWithSubscription('trial', [
            'trial_ends_at'      => now()->addDays(2),
            'current_period_end' => now()->addDays(2),
        ]);

        $this->runCommand();

        $this->assertSame('trial', $sub->fresh()->status);
        Mail::assertNotQueued(TrialExpiredMail::class);
    }

    // ---------- active -> past_due ----------

    public function test_una_suscripcion_pagada_vencida_pasa_a_past_due(): void
    {
        Mail::fake();

        $sub = $this->companyWithSubscription('active', [
            'current_period_end' => now()->subHour(),
        ]);

        $this->runCommand();

        $this->assertSame('past_due', $sub->fresh()->status);
    }

    /**
     * Los días de gracia existen para que un cobro rechazado no tumbe de inmediato la
     * presencia del cliente: en past_due la tarjeta sigue online.
     */
    public function test_en_past_due_la_tarjeta_publica_sigue_online(): void
    {
        $sub = $this->companyWithSubscription('active', [
            'current_period_end' => now()->subHour(),
        ]);

        $this->runCommand();

        $this->assertTrue($sub->fresh()->company->hasPublicAccess());
    }

    // ---------- past_due -> expired ----------

    public function test_past_due_expira_al_pasar_los_dias_de_gracia(): void
    {
        Mail::fake();
        AppSetting::set('grace_period_days', 10);

        $sub = $this->companyWithSubscription('past_due', [
            'current_period_end' => now()->subDays(11),
        ]);

        $this->runCommand();

        $this->assertSame('expired', $sub->fresh()->status);
        $this->assertFalse($sub->fresh()->company->hasPublicAccess());
        Mail::assertQueued(SubscriptionExpiredMail::class);
    }

    public function test_past_due_dentro_de_la_gracia_no_expira(): void
    {
        Mail::fake();
        AppSetting::set('grace_period_days', 10);

        $sub = $this->companyWithSubscription('past_due', [
            'current_period_end' => now()->subDays(3),
        ]);

        $this->runCommand();

        $this->assertSame('past_due', $sub->fresh()->status);
        Mail::assertNotQueued(SubscriptionExpiredMail::class);
    }

    public function test_los_dias_de_gracia_son_configurables(): void
    {
        Mail::fake();
        AppSetting::set('grace_period_days', 2);

        $sub = $this->companyWithSubscription('past_due', [
            'current_period_end' => now()->subDays(3),
        ]);

        $this->runCommand();

        $this->assertSame('expired', $sub->fresh()->status);
    }

    /**
     * Si el cron no corre por unos días, al volver debe ponerse al día de una vez: la
     * gracia se mide desde el fin del periodo, no desde que el comando se entera. Una
     * suscripción vencida hace más días que la gracia recorre active → past_due →
     * expired en una sola pasada, y así debe ser.
     */
    public function test_recupera_el_atraso_si_el_cron_no_corrio(): void
    {
        Mail::fake();
        AppSetting::set('grace_period_days', 10);

        $sub = $this->companyWithSubscription('active', [
            'current_period_end' => now()->subDays(15),
        ]);

        $this->runCommand();

        $this->assertSame('expired', $sub->fresh()->status);
    }

    // ---------- Idempotencia y borde ----------

    /**
     * El comando corre a diario: una segunda pasada no puede reenviar los correos de
     * vencimiento ya enviados.
     */
    public function test_correr_el_comando_dos_veces_no_reenvia_los_avisos(): void
    {
        Mail::fake();

        $this->companyWithSubscription('trial', [
            'trial_ends_at'      => now()->subHour(),
            'current_period_end' => now()->subHour(),
        ]);

        $this->runCommand();
        $this->runCommand();

        Mail::assertQueuedCount(1);
    }

    /**
     * Una suscripción ya cancelada o expirada no debe volver a moverse ni a generar
     * correos en cada corrida diaria.
     */
    public function test_las_suscripciones_ya_cerradas_no_se_vuelven_a_procesar(): void
    {
        Mail::fake();

        $expirada  = $this->companyWithSubscription('expired', ['current_period_end' => now()->subYear()]);
        $cancelada = $this->companyWithSubscription('cancelled', ['current_period_end' => now()->subYear()]);

        $this->runCommand();

        $this->assertSame('expired', $expirada->fresh()->status);
        $this->assertSame('cancelled', $cancelada->fresh()->status);
        Mail::assertNothingQueued();
    }

    /**
     * Recorrido completo de un cliente que nunca paga: prueba → expirado, con la tarjeta
     * pública cayéndose en el momento correcto.
     */
    public function test_recorrido_completo_de_un_cliente_que_no_paga(): void
    {
        Mail::fake();
        AppSetting::set('grace_period_days', 10);

        $sub = $this->companyWithSubscription('trial', [
            'trial_ends_at'      => now()->addDays(7),
            'current_period_end' => now()->addDays(7),
        ]);
        $company = $sub->company;

        // Durante la prueba la tarjeta está publicada.
        $this->assertTrue($company->hasPublicAccess());

        // Vence el trial.
        $this->travel(8)->days();
        $this->runCommand();

        $this->assertSame('expired', $sub->fresh()->status);
        $this->assertFalse($company->fresh()->hasPublicAccess());
    }
}
