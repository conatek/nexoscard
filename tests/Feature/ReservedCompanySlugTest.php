<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * La tarjeta pública vive en /{slug}. Una empresa con el slug de una ruta de la SPA
 * (/landing, /politica-privacidad…) quedaría tapada por esa ruta y su tarjeta sería
 * inalcanzable, así que esos slugs no se pueden asignar ni al registrarse ni al editar.
 */
class ReservedCompanySlugTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Role::findOrCreate('Admin', 'web');

        Plan::query()->delete();
        Plan::create([
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

    public function test_registro_esquiva_un_slug_reservado(): void
    {
        $this->postJson('/api/register', [
            'name'                  => 'Landing',
            'email'                 => 'landing@test.com',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ])->assertCreated();

        $slug = User::where('email', 'landing@test.com')->first()->company->slug;

        $this->assertSame('landing-1', $slug);
    }

    public function test_editar_empresa_rechaza_un_slug_reservado(): void
    {
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $user    = User::create([
            'company_id' => $company->id,
            'name'       => 'Dueño',
            'email'      => 'duenio@test.com',
            'password'   => bcrypt('secret123'),
        ]);
        $user->assignRole('Admin');
        $company->update(['user_id' => $user->id]);

        $this->actingAs($user)
            ->putJson("/api/companies/{$company->id}", [
                'name' => 'Acme',
                'slug' => 'politica-privacidad',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['slug']);

        $this->assertSame('acme', $company->fresh()->slug);
    }
}
