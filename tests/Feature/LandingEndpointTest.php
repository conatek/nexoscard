<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * `GET /api/landing` alimenta la landing pública: precio, oferta, días de prueba y
 * contacto salen del admin, y la página no debe mostrar un precio que ya no se cobra.
 */
class LandingEndpointTest extends TestCase
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

    public function test_es_publico_y_devuelve_el_precio_vigente(): void
    {
        AppSetting::set('support_whatsapp', '+57 302 221 8054');

        $this->getJson('/api/landing')
            ->assertOk()
            ->assertJsonPath('plan.display_name', 'Presencia Digital')
            ->assertJsonPath('plan.effective_price', 39000)
            ->assertJsonPath('plan.is_offer_active', true)
            ->assertJsonPath('plan.discount_percent', 43)
            ->assertJsonPath('trial_days', 7)
            ->assertJsonPath('contact.support_whatsapp', '573022218054');
    }

    public function test_con_la_oferta_vencida_muestra_el_precio_regular(): void
    {
        $this->plan->update(['offer_ends_at' => now()->subDay()]);

        $this->getJson('/api/landing')
            ->assertOk()
            ->assertJsonPath('plan.effective_price', 69000)
            ->assertJsonPath('plan.is_offer_active', false);
    }
}
