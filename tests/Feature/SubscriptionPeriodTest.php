<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Antes había tres sitios activando suscripciones y cada uno parcheaba
 * `current_period_end` con su propio `addYear()`. Ahora el periodo sale de
 * `Plan::periodEnd()`: estos tests fijan ese contrato.
 */
class SubscriptionPeriodTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // La migración de datos ya siembra "Presencia Digital". Se limpia para que cada
        // test controle exactamente qué planes existen.
        Plan::query()->delete();
    }

    private function plan(array $attributes = []): Plan
    {
        return Plan::create(array_merge([
            'name'           => 'presencia-digital',
            'display_name'   => 'Presencia Digital',
            'price_regular'  => 69900,
            'offer_price'    => 39900,
            'billing_period' => 'yearly',
            'max_cards'      => 1,
            'is_active'      => true,
            'is_default'     => true,
        ], $attributes));
    }

    private function company(): Company
    {
        return Company::create(['name' => 'Acme', 'slug' => 'acme']);
    }

    public function test_activar_un_plan_anual_da_un_ano(): void
    {
        $plan    = $this->plan();
        $company = $this->company();

        $subscription = app(SubscriptionService::class)
            ->activateSubscription($company, $plan, 'mercadopago');

        $this->assertSame('active', $subscription->status);
        $this->assertSame('yearly', $subscription->billing_period);
        $this->assertSame(
            now()->addYear()->toDateString(),
            $subscription->current_period_end->toDateString()
        );
    }

    public function test_activar_un_plan_mensual_da_un_mes(): void
    {
        $plan    = $this->plan(['billing_period' => 'monthly']);
        $company = $this->company();

        $subscription = app(SubscriptionService::class)
            ->activateSubscription($company, $plan, 'mercadopago');

        $this->assertSame(
            now()->addMonth()->toDateString(),
            $subscription->current_period_end->toDateString()
        );
    }

    public function test_activar_cancela_la_suscripcion_anterior(): void
    {
        $plan    = $this->plan();
        $company = $this->company();

        $anterior = Subscription::create([
            'company_id'           => $company->id,
            'plan_id'              => $plan->id,
            'status'               => 'trial',
            'current_period_start' => now(),
            'current_period_end'   => now()->addDays(7),
        ]);

        app(SubscriptionService::class)->activateSubscription($company, $plan);

        $this->assertSame('cancelled', $anterior->fresh()->status);
        $this->assertNotNull($anterior->fresh()->cancelled_at);
    }

    public function test_el_trial_corre_sobre_el_plan_por_defecto(): void
    {
        $this->plan();
        $company = $this->company();

        $subscription = app(SubscriptionService::class)->createTrialSubscription($company);

        $this->assertSame('trial', $subscription->status);
        $this->assertSame(Plan::default()->id, $subscription->plan_id);
        $this->assertNotNull($subscription->trial_ends_at);
    }

    public function test_plan_default_cae_al_primer_plan_activo_si_ninguno_es_default(): void
    {
        $this->plan(['is_default' => false]);

        // Si nadie es default, el registro de usuarios no debe reventar.
        $this->assertNotNull(Plan::default());
    }

    public function test_plan_default_ignora_planes_inactivos(): void
    {
        $this->plan(['is_active' => false]);

        $this->assertNull(Plan::default());
    }
}
