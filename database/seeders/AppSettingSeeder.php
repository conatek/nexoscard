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
                'value'       => '30',
                'type'        => 'integer',
                'description' => 'Dias de periodo de prueba para nuevos registros',
            ],
            [
                'key'         => 'grace_period_days',
                'value'       => '10',
                'type'        => 'integer',
                'description' => 'Dias de gracia despues de vencimiento antes de expirar completamente',
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
