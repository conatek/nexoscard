<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'         => 'trial_days',
                'value'       => '7',
                'type'        => 'integer',
                'description' => 'Dias de periodo de prueba para nuevos registros',
            ],
            [
                'key'         => 'trial_reminder_days',
                'value'       => '3,1',
                'type'        => 'string',
                'description' => 'Dias antes del vencimiento en que se envia recordatorio (separados por coma)',
            ],
            [
                'key'         => 'renewal_reminder_days',
                'value'       => '15,3',
                'type'        => 'string',
                'description' => 'Dias antes de la renovacion en que se avisa a quien ya paga (separados por coma)',
            ],
            [
                'key'         => 'grace_period_days',
                'value'       => '10',
                'type'        => 'integer',
                'description' => 'Dias de gracia despues de vencimiento antes de expirar completamente',
            ],
            [
                'key'         => 'support_whatsapp',
                'value'       => '573022218054',
                'type'        => 'string',
                'description' => 'Numero de WhatsApp de soporte y cotizaciones, con indicativo y sin signos (ej: 573001234567)',
            ],
            [
                'key'         => 'support_email',
                'value'       => 'soporte@nexoscard.com',
                'type'        => 'string',
                'description' => 'Email de soporte mostrado en correos',
            ],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
