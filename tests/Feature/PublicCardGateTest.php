<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * La tarjeta pública sale de línea cuando la suscripción vence: es el incentivo real de
 * conversión. Pero el dueño debe conservar acceso a su panel para poder pagar y
 * reactivarla — si eso se rompe, el cliente queda encerrado fuera.
 */
class PublicCardGateTest extends TestCase
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
            'price_regular'  => 69900,
            'offer_price'    => 39900,
            'billing_period' => 'yearly',
            'max_cards'      => 1,
            'is_active'      => true,
            'is_default'     => true,
        ]);
    }

    private function companyWithStatus(?string $status, string $slug): Company
    {
        $company = Company::create(['name' => 'Acme ' . $slug, 'slug' => $slug]);

        if ($status !== null) {
            Subscription::create([
                'company_id'           => $company->id,
                'plan_id'              => $this->plan->id,
                'status'               => $status,
                'current_period_start' => now()->subMonth(),
                'current_period_end'   => $status === 'active' ? now()->addMonth() : now()->subDay(),
            ]);
        }

        return $company;
    }

    public static function estadosQueSirven(): array
    {
        return [
            'trial'    => ['trial'],
            'active'   => ['active'],
            // En gracia la tarjeta sigue online: un cobro rechazado no debe tumbar de
            // inmediato la presencia del cliente.
            'past_due' => ['past_due'],
        ];
    }

    public static function estadosQueBloquean(): array
    {
        return [
            'expired'   => ['expired'],
            'cancelled' => ['cancelled'],
        ];
    }

    /** @dataProvider estadosQueSirven */
    public function test_estados_vigentes_sirven_la_tarjeta(string $status): void
    {
        $company = $this->companyWithStatus($status, 'vigente-' . $status);

        $this->assertTrue($company->hasPublicAccess());
        $this->getJson("/api/public/{$company->slug}")->assertOk();
    }

    /** @dataProvider estadosQueBloquean */
    public function test_estados_vencidos_devuelven_402(string $status): void
    {
        $company = $this->companyWithStatus($status, 'vencida-' . $status);

        $this->assertFalse($company->hasPublicAccess());

        $this->getJson("/api/public/{$company->slug}")
            ->assertStatus(402)
            ->assertJson([
                'available' => false,
                'reason'    => 'subscription_expired',
            ]);
    }

    public function test_empresa_sin_suscripcion_queda_bloqueada(): void
    {
        $company = $this->companyWithStatus(null, 'sin-suscripcion');

        $this->assertFalse($company->hasPublicAccess());
        $this->getJson("/api/public/{$company->slug}")->assertStatus(402);
    }

    public function test_slug_inexistente_da_404_y_no_402(): void
    {
        // No debe revelarse la diferencia entre "no existe" y "no está al día".
        $this->getJson('/api/public/no-existe-jamas')->assertStatus(404);
    }

    public function test_el_dueno_de_una_empresa_vencida_sigue_entrando_a_su_panel(): void
    {
        $company = $this->companyWithStatus('expired', 'vencida-panel');

        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $owner = User::create([
            'company_id' => $company->id,
            'name'       => 'Dueño',
            'email'      => 'duenio@vencida.com',
            'password'   => bcrypt('secret123'),
        ]);
        $owner->assignRole('Admin');
        $company->update(['user_id' => $owner->id]);

        $this->actingAs($owner)->getJson("/api/companies/{$company->id}")->assertOk();
        $this->actingAs($owner)->getJson('/api/subscription')->assertOk();

        // El editor de plantillas también debe seguir vivo para poder preparar la
        // tarjeta antes de reactivar.
        $this->actingAs($owner)->getJson('/api/templates')->assertOk();
    }

    public function test_la_suscripcion_mas_reciente_manda_sobre_las_viejas(): void
    {
        $company = $this->companyWithStatus('expired', 'reactivada');

        // Tras pagar, la nueva suscripción activa debe devolver la tarjeta a línea
        // aunque quede una expirada más antigua en el historial.
        Subscription::create([
            'company_id'           => $company->id,
            'plan_id'              => $this->plan->id,
            'status'               => 'active',
            'current_period_start' => now(),
            'current_period_end'   => now()->addYear(),
        ]);

        $this->assertTrue($company->fresh()->hasPublicAccess());
        $this->getJson("/api/public/{$company->slug}")->assertOk();
    }
}
