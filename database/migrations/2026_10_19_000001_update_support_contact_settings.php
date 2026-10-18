<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Nuevo contacto de soporte (pedido del 2026-10-19).
 *
 * - Correo: soporte@nexoscard.co no existe (el dominio .co no tiene MX y los correos rebotaban);
 *   pasa a infonexoscard@gmail.com.
 * - WhatsApp: 573043903089 pasa a 573206221447.
 *
 * Los dos valores se editan desde Admin > Configuracion, asi que viven en app_settings: cambiarlos
 * solo en el codigo no tocaria produccion. La pagina de inicio, la guia, el panel de cuenta, la
 * pagina de planes y los correos los leen de aqui.
 */
return new class extends Migration
{
    private const NUEVOS = [
        'support_email'    => 'infonexoscard@gmail.com',
        'support_whatsapp' => '573206221447',
    ];

    private const ANTERIORES = [
        'support_email'    => 'soporte@nexoscard.co',
        'support_whatsapp' => '573043903089',
    ];

    public function up(): void
    {
        $this->guardar(self::NUEVOS);
    }

    public function down(): void
    {
        $this->guardar(self::ANTERIORES);
    }

    private function guardar(array $valores): void
    {
        $now = now();

        foreach ($valores as $key => $value) {
            $existe = DB::table('app_settings')->where('key', $key)->exists();

            DB::table('app_settings')->updateOrInsert(
                ['key' => $key],
                array_merge(
                    ['value' => $value, 'updated_at' => $now],
                    $existe ? [] : [
                        'type'        => 'string',
                        'description' => $key === 'support_email'
                            ? 'Email de soporte mostrado en correos'
                            : 'Numero de WhatsApp de soporte y cotizaciones, con indicativo y sin signos (ej: 573001234567)',
                        'created_at'  => $now,
                    ]
                )
            );

            Cache::forget("app_setting_{$key}");
        }
    }
};
