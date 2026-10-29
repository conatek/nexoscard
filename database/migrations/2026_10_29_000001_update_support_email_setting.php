<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

/**
 * Correo de soporte del dominio propio (pedido del 2026-10-29): infonexoscard@gmail.com pasa a
 * info@nexoscard.com, el que va en el pie de página y en la sección Contacto de /inicio.
 *
 * Vive en app_settings (se edita en Admin > Configuración), así que cambiarlo solo en el código
 * no tocaría producción.
 */
return new class extends Migration
{
    private const NUEVO = 'info@nexoscard.com';

    private const ANTERIOR = 'infonexoscard@gmail.com';

    public function up(): void
    {
        $this->guardar(self::NUEVO);
    }

    public function down(): void
    {
        $this->guardar(self::ANTERIOR);
    }

    private function guardar(string $email): void
    {
        $now = now();
        $existe = DB::table('app_settings')->where('key', 'support_email')->exists();

        DB::table('app_settings')->updateOrInsert(
            ['key' => 'support_email'],
            array_merge(
                ['value' => $email, 'updated_at' => $now],
                $existe ? [] : [
                    'type'        => 'string',
                    'description' => 'Email de soporte mostrado en correos',
                    'created_at'  => $now,
                ]
            )
        );

        Cache::forget('app_setting_support_email');
    }
};
