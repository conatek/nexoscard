<?php

namespace Tests\Feature;

use App\Mail\TrialExpiredMail;
use App\Mail\WelcomeTrialMail;
use App\Models\AppSetting;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Ajustes del 2026-10-19: la guía de primeros pasos pasa a ser una página de la app
 * (/guia-usuario), cambian el correo y el WhatsApp de soporte, y la página de inicio
 * estrena imagen al compartirse.
 */
class GuideAndSupportContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_guia_es_publica_y_trae_su_vista_previa_para_whatsapp(): void
    {
        $this->get('/guia-usuario')
            ->assertOk()
            ->assertSee('<meta property="og:title" content="Guía de primeros pasos | NEXOS Card" />', false)
            ->assertSee('<meta property="og:url" content="' . url('guia-usuario') . '" />', false)
            ->assertSee('images/landing/og-inicio.jpg', false);
    }

    public function test_la_pagina_de_inicio_comparte_la_imagen_nueva(): void
    {
        $this->get('/inicio')
            ->assertOk()
            ->assertSee('images/landing/og-inicio.jpg', false)
            ->assertDontSee('og-landing.jpg', false);

        $this->assertFileExists(public_path('images/landing/og-inicio.jpg'));
    }

    public function test_ninguna_empresa_puede_tomar_el_slug_de_la_guia(): void
    {
        $this->assertContains('guia-usuario', Company::RESERVED_SLUGS);
    }

    public function test_la_migracion_del_19_de_octubre_reemplaza_el_correo_y_el_whatsapp(): void
    {
        // Lo que tenía producción antes del cambio.
        AppSetting::set('support_email', 'soporte@nexoscard.co');
        AppSetting::set('support_whatsapp', '573043903089');
        $this->assertSame('soporte@nexoscard.co', AppSetting::getSupportEmail());   // queda en caché

        $migration = require database_path('migrations/2026_10_19_000001_update_support_contact_settings.php');
        $migration->up();

        $this->assertSame('infonexoscard@gmail.com', AppSetting::getSupportEmail());
        $this->assertSame('573206221447', AppSetting::getSupportWhatsapp());
        $this->getJson('/api/landing')
            ->assertJsonPath('contact.support_email', 'infonexoscard@gmail.com')
            ->assertJsonPath('contact.support_whatsapp', '573206221447');
    }

    public function test_sin_configuracion_el_correo_por_defecto_es_el_nuevo(): void
    {
        DB::table('app_settings')->where('key', 'support_email')->delete();
        cache()->forget('app_setting_support_email');

        // Desde el 2026-10-29 el correo por defecto es el del dominio propio.
        $this->assertSame('info@nexoscard.com', AppSetting::getSupportEmail());
    }

    public function test_el_correo_de_bienvenida_enlaza_la_guia_de_la_app(): void
    {
        // El .env de produccion tenia MAIL_USER_GUIDE_URL apuntando al PDF de Drive: el
        // enlace ya no depende de esa variable.
        config(['mail.user_guide_url' => 'https://drive.google.com/file/d/x/view']);
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $user = User::create([
            'name'       => 'Laura',
            'email'      => 'laura@test.com',
            'password'   => bcrypt('secret123'),
            'company_id' => $company->id,
        ]);

        $html = (new WelcomeTrialMail($user, $company, 7, now()->addDays(7)))->render();

        $this->assertStringContainsString('href="' . url('/guia-usuario') . '"', $html);
        $this->assertStringNotContainsString('drive.google.com', $html);
    }

    public function test_el_correo_de_prueba_vencida_usa_el_correo_configurado(): void
    {
        AppSetting::set('support_email', 'info@nexoscard.com');
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $user = User::create([
            'name'       => 'Laura',
            'email'      => 'laura@test.com',
            'password'   => bcrypt('secret123'),
            'company_id' => $company->id,
        ]);

        $html = (new TrialExpiredMail($user, $company))->render();

        $this->assertStringContainsString('mailto:info@nexoscard.com', $html);
        $this->assertStringNotContainsString('soporte@nexoscard', $html);
    }

    public function test_la_migracion_del_29_de_octubre_pasa_el_correo_al_dominio_propio(): void
    {
        // Lo que tenía producción antes del cambio.
        AppSetting::set('support_email', 'infonexoscard@gmail.com');
        $this->assertSame('infonexoscard@gmail.com', AppSetting::getSupportEmail());   // queda en caché

        $migration = require database_path('migrations/2026_10_29_000001_update_support_email_setting.php');
        $migration->up();

        $this->assertSame('info@nexoscard.com', AppSetting::getSupportEmail());
        $this->getJson('/api/landing')->assertJsonPath('contact.support_email', 'info@nexoscard.com');

        $migration->down();

        $this->assertSame('infonexoscard@gmail.com', AppSetting::getSupportEmail());
    }
}
