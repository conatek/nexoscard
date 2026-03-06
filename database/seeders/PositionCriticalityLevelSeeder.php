<?php

namespace Database\Seeders;

use App\Models\PositionCriticalityLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionCriticalityLevelSeeder extends Seeder
{
    public function run()
    {
        PositionCriticalityLevel::create([
            'level' => 'Alto',
            'description' => 'El cargo está asociado a etapas críticas o áreas sensibles de la empresa que conllevan un riesgo alto de fraude.',
        ]);
        PositionCriticalityLevel::create([
            'level' => 'Medio',
            'description' => 'Este cargo de acuerdo a sus funciones y niveles de autorización tiene un riesgo moderado respecto al fraude.',
        ]);
        PositionCriticalityLevel::create([
            'level' => 'Bajo',
            'description' => 'Este cargo de acuerdo a sus funciones y niveles de autorización o acceso a información tiene un riesgo bajo frente al fraude.',
        ]);
    }
}
