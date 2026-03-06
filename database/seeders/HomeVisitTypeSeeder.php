<?php

namespace Database\Seeders;

use App\Models\HomeVisitType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomeVisitTypeSeeder extends Seeder
{
    public function run()
    {
        HomeVisitType::create(['name' => 'Verificación de dirección']);
        HomeVisitType::create(['name' => 'Investigación de ausencias o incapacidades']);
        HomeVisitType::create(['name' => 'Revisión de condiciones laborales específicas']);
        HomeVisitType::create(['name' => 'Seguimiento médico o bienestar']);
        HomeVisitType::create(['name' => 'Entrega o recopilación de documentos']);
        HomeVisitType::create(['name' => 'Chequeo de referencias personales']);
        HomeVisitType::create(['name' => 'Verificación de condiciones de vivienda']);
        HomeVisitType::create(['name' => 'Investigación de seguridad o cumplimiento']);
    }
}
