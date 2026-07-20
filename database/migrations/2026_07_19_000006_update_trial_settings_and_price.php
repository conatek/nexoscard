<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Trial a 7 días (estaba en 30) y cadencia de recordatorios a 3 y 1 día.
 *
 * También corrige el precio de "Presencia Digital" a $69.000 / $39.000: la pieza gráfica
 * de la oferta manda sobre el PDF, que decía $69.900 / $39.900. El 43% de descuento se
 * mantiene en ambos casos.
 *
 * El `Cache::forget` es imprescindible: `AppSetting::get()` cachea una hora, así que sin
 * él el cambio de trial_days no surtiría efecto hasta la siguiente hora.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('app_settings')->updateOrInsert(
            ['key' => 'trial_days'],
            [
                'value'       => '7',
                'type'        => 'integer',
                'description' => 'Dias de periodo de prueba para nuevos registros',
                'updated_at'  => $now,
                'created_at'  => $now,
            ]
        );

        DB::table('app_settings')->updateOrInsert(
            ['key' => 'trial_reminder_days'],
            [
                'value'       => '3,1',
                'type'        => 'string',
                'description' => 'Dias antes del vencimiento en que se envia recordatorio (separados por coma)',
                'updated_at'  => $now,
                'created_at'  => $now,
            ]
        );

        Cache::forget('app_setting_trial_days');
        Cache::forget('app_setting_trial_reminder_days');

        DB::table('plans')
            ->where('name', 'presencia-digital')
            ->update([
                'price_regular' => 69000,
                'offer_price'   => 39000,
                'updated_at'    => $now,
            ]);
    }

    public function down(): void
    {
        $now = now();

        DB::table('app_settings')->where('key', 'trial_days')->update([
            'value'      => '30',
            'updated_at' => $now,
        ]);

        DB::table('app_settings')->where('key', 'trial_reminder_days')->delete();

        Cache::forget('app_setting_trial_days');
        Cache::forget('app_setting_trial_reminder_days');

        DB::table('plans')
            ->where('name', 'presencia-digital')
            ->update([
                'price_regular' => 69900,
                'offer_price'   => 39900,
                'updated_at'    => $now,
            ]);
    }
};
