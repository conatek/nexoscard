<?php

namespace Database\Seeders;

use App\Models\PositionRiskClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionRiskClassSeeder extends Seeder
{
    public function run()
    {
        PositionRiskClass::create([
            'class' => 'CLASE I',
            'description' => 'Riesgo mínimo',
            'quote_percentage' => 0.522,
        ]);
        PositionRiskClass::create([
            'class' => 'CLASE II',
            'description' => 'Riesgo bajo',
            'quote_percentage' => 1.044,
        ]);
        PositionRiskClass::create([
            'class' => 'CLASE III',
            'description' => 'Riesgo medio',
            'quote_percentage' => 2.436,
        ]);
        PositionRiskClass::create([
            'class' => 'CLASE IV',
            'description' => 'Riesgo alto',
            'quote_percentage' => 4.350,
        ]);
        PositionRiskClass::create([
            'class' => 'CLASE V',
            'description' => 'Riesgo máximo',
            'quote_percentage' => 6.960,
        ]);
    }
}
