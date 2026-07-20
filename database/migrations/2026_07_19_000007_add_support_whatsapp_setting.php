<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Canal de contacto por WhatsApp para cotizaciones de equipos y soporte.
 *
 * El bloque de "precio especial para equipos" de la página de planes apuntaba a un
 * `mailto:`, que no hace nada si el usuario no tiene cliente de correo configurado
 * (el caso habitual en escritorio con webmail).
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('app_settings')->updateOrInsert(
            ['key' => 'support_whatsapp'],
            [
                'value'       => '573022218054',
                'type'        => 'string',
                'description' => 'Numero de WhatsApp de soporte y cotizaciones, con indicativo y sin signos (ej: 573001234567)',
                'updated_at'  => $now,
                'created_at'  => $now,
            ]
        );

        Cache::forget('app_setting_support_whatsapp');
    }

    public function down(): void
    {
        DB::table('app_settings')->where('key', 'support_whatsapp')->delete();
        Cache::forget('app_setting_support_whatsapp');
    }
};
