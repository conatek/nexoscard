<?php

namespace Database\Seeders;

use App\Models\ArlType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArlTypeSeeder extends Seeder
{
    public function run()
    {
        ArlType::create(['name' => 'Liberty Seguros de Vida']);
        ArlType::create(['name' => 'Mapfre Colombia Vida Seguros']);
        ArlType::create(['name' => 'Positiva']);
        ArlType::create(['name' => 'Riesgos Laborales Colmena']);
        ArlType::create(['name' => 'Seguros Bolívar']);
        ArlType::create(['name' => 'Seguros de Vida Alfa']);
        ArlType::create(['name' => 'Seguros de Vida Aurora']);
        ArlType::create(['name' => 'Seguros de Vida Colpatria']);
        ArlType::create(['name' => 'Seguros de Vida La Equidad']);
        ArlType::create(['name' => 'Suramericana de Seguros de Vida']);
    }
}
