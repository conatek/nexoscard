<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Cadencia de los avisos de renovación para quien ya paga.
 *
 * El plan es anual y no se renueva solo: MercadoPago cobra una vez, no hay suscripción
 * recurrente. Hasta ahora solo se avisaba durante la prueba, así que el cliente que había
 * pagado se enteraba del vencimiento cuando su tarjeta ya estaba fuera de línea.
 *
 * La cadencia es más holgada que la del trial (15 y 3, no 3 y 1) porque renovar cuesta
 * dinero y hay que dar margen para decidirlo.
 *
 * `AppSetting::getRenewalReminderDays()` ya trae este mismo valor por defecto, así que la
 * fila no es imprescindible para que funcione: sirve para que el Master la vea y la pueda
 * ajustar desde el panel de configuración, que se arma con lo que hay en la tabla.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('app_settings')->updateOrInsert(
            ['key' => 'renewal_reminder_days'],
            [
                'value'       => '15,3',
                'type'        => 'string',
                'description' => 'Dias antes de la renovacion en que se avisa a quien ya paga (separados por coma)',
                'updated_at'  => $now,
                'created_at'  => $now,
            ]
        );

        Cache::forget('app_setting_renewal_reminder_days');
    }

    public function down(): void
    {
        DB::table('app_settings')->where('key', 'renewal_reminder_days')->delete();
        Cache::forget('app_setting_renewal_reminder_days');
    }
};
