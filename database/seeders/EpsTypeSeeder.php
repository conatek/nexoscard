<?php

namespace Database\Seeders;

use App\Models\EpsType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EpsTypeSeeder extends Seeder
{
    public function run()
    {
        EpsType::create(['name' => 'Aliansalud']);
        EpsType::create(['name' => 'Anas Wayuu']);
        EpsType::create(['name' => 'Asociación Indígena del Cauca AIC']);
        EpsType::create(['name' => 'Capital Salud']);
        EpsType::create(['name' => 'Capresoca']);
        EpsType::create(['name' => 'Convida']);
        EpsType::create(['name' => 'Comfenalco Valle']);
        EpsType::create(['name' => 'Compensar']);
        EpsType::create(['name' => 'Coosalud']);
        EpsType::create(['name' => 'Dusakawi']);
        EpsType::create(['name' => 'Famisanar']);
        EpsType::create(['name' => 'Mellamas']);
        EpsType::create(['name' => 'Mutualser']);
        EpsType::create(['name' => 'Nueva']);
        EpsType::create(['name' => 'Pijaos Salud']);
        EpsType::create(['name' => 'Salud Mía']);
        EpsType::create(['name' => 'Salud Total']);
        EpsType::create(['name' => 'Saludvida']);
        EpsType::create(['name' => 'Sanitas']);
        EpsType::create(['name' => 'Savia Salud']);
        EpsType::create(['name' => 'Servicio Occidental de Salud']);
        EpsType::create(['name' => 'Sura']);
    }
}
