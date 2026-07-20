<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

/**
 * El monto lo decide siempre el servidor. Si el cliente pudiera influir en el precio,
 * cualquiera pagaría lo que quisiera.
 */
class PaymentAmountTest extends TestCase
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
            'price_regular'  => 69900,
            'offer_price'    => 39900,
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

    private function mockApprovedGateway(): void
    {
        $mock = Mockery::mock(MercadoPagoService::class);
        $mock->shouldReceive('createPayment')->andReturn([
            'id'                => 'MP-TEST-1',
            'status'            => 'approved',
            'status_detail'     => 'accredited',
            'payment_method_id' => 'visa',
            'payment_type_id'   => 'credit_card',
            'date_approved'     => now()->toIso8601String(),
        ]);
        $mock->shouldReceive('mapStatus')->andReturn('approved');
        $mock->shouldReceive('mapPaymentMethod')->andReturn('credit_card');

        $this->app->instance(MercadoPagoService::class, $mock);
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

    public function test_cobra_el_precio_de_oferta_ignorando_lo_que_mande_el_cliente(): void
    {
        $this->mockApprovedGateway();

        $this->actingAs($this->user)
            ->postJson('/api/payments/process', $this->payload([
                // Intento de manipulación: el cliente dice que vale 1 peso.
                'amount'         => 1,
                'price_regular'  => 1,
            ]))
            ->assertOk();

        $this->assertSame(39900.0, (float) Payment::first()->amount);
    }

    public function test_rechaza_si_el_precio_visto_por_el_cliente_ya_no_es_el_vigente(): void
    {
        $this->mockApprovedGateway();

        $this->actingAs($this->user)
            ->postJson('/api/payments/process', $this->payload([
                'expected_amount' => 39900,
            ]))
            ->assertOk();

        // Ahora la oferta vence: el cliente que aún tenía $39.900 en pantalla debe ser
        // frenado en vez de cobrado por $69.900 en silencio.
        $this->plan->update(['offer_ends_at' => now()->subMinute()]);

        $this->actingAs($this->user)
            ->postJson('/api/payments/process', $this->payload([
                'expected_amount' => 39900,
            ]))
            ->assertStatus(422)
            ->assertJson(['price_changed' => true, 'current_amount' => 69900]);
    }

    public function test_con_la_oferta_vencida_cobra_el_precio_regular(): void
    {
        $this->mockApprovedGateway();
        $this->plan->update(['offer_ends_at' => now()->subMinute()]);

        $this->actingAs($this->user)
            ->postJson('/api/payments/process', $this->payload([
                'expected_amount' => 69900,
            ]))
            ->assertOk();

        $this->assertSame(69900.0, (float) Payment::first()->amount);
    }

    public function test_un_plan_sin_precio_no_se_puede_pagar(): void
    {
        $this->plan->update(['price_regular' => 0, 'offer_price' => null]);

        $this->actingAs($this->user)
            ->postJson('/api/payments/process', $this->payload())
            ->assertStatus(422);

        $this->assertSame(0, Payment::count());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
