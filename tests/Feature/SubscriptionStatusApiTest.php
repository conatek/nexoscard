<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * `GET /api/subscription` y `GET /api/dashboard` alimentan al banner, al panel de cuenta
 * y a "Mi Plan". Los tres tienen que poder distinguir "vencido" de "esta empresa nunca
 * tuvo suscripcion": es justo en el vencimiento cuando hay que empujar a pagar.
 */
class SubscriptionStatusApiTest extends TestCase
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

    private function subscription(string $status, array $overrides = []): Subscription
    {
        return Subscription::create(array_merge([
            'company_id'           => $this->company->id,
            'plan_id'              => $this->plan->id,
            'billing_period'       => 'yearly',
            'status'               => $status,
            'current_period_start' => now()->subYear(),
            'current_period_end'   => now()->subDay(),
        ], $overrides));
    }

    /**
     * @dataProvider estadosVencidos
     */
    public function test_los_estados_vencidos_llegan_al_frontend(string $status): void
    {
        $this->subscription($status);

        $this->actingAs($this->user)
            ->getJson('/api/subscription')
            ->assertOk()
            ->assertJsonPath('subscription.status', $status);
    }

    public static function estadosVencidos(): array
    {
        return [
            'past_due'  => ['past_due'],
            'expired'   => ['expired'],
            'cancelled' => ['cancelled'],
        ];
    }

    public function test_una_empresa_sin_suscripcion_sigue_devolviendo_null(): void
    {
        $this->actingAs($this->user)
            ->getJson('/api/subscription')
            ->assertOk()
            ->assertJsonPath('subscription', null);
    }

    /**
     * El banner decide que mensaje pintar con `subscription.status`. Si llega null en
     * vencido, se muestra vacio y sin boton de renovar justo cuando mas importa.
     */
    public function test_el_dashboard_tambien_reporta_el_estado_vencido(): void
    {
        $this->subscription('expired');

        $this->actingAs($this->user)
            ->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('subscription.status', 'expired');
    }

    /**
     * Al renovar conviven la suscripcion vieja (cancelled) y la nueva (active). Debe
     * mandar la nueva, no la ultima que se toco.
     */
    public function test_una_suscripcion_activa_le_gana_a_las_viejas(): void
    {
        $this->subscription('cancelled');
        $this->subscription('active', [
            'current_period_start' => now(),
            'current_period_end'   => now()->addYear(),
        ]);

        $this->actingAs($this->user)
            ->getJson('/api/subscription')
            ->assertOk()
            ->assertJsonPath('subscription.status', 'active');
    }

    /**
     * En trial los dias se cuentan sobre `trial_ends_at`, y el numero tiene que ser el
     * mismo que ve el usuario en el correo y en el banner.
     */
    public function test_reporta_los_dias_restantes_del_trial(): void
    {
        $this->subscription('trial', [
            'trial_ends_at'        => now()->addDays(3),
            'current_period_start' => now(),
            'current_period_end'   => now()->addDays(3),
        ]);

        $this->actingAs($this->user)
            ->getJson('/api/subscription')
            ->assertOk()
            ->assertJsonPath('subscription.status', 'trial')
            ->assertJsonPath('days_remaining', 3);
    }
}
