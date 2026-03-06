<?php

namespace Database\Seeders;

use App\Models\HousingTenure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HousingTenureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HousingTenure::create(['type' => 'Arrendada']);
        HousingTenure::create(['type' => 'Familiar']);
        HousingTenure::create(['type' => 'Propia']);
        HousingTenure::create(['type' => 'Con permiso del propietario (Sin pago)']);
        HousingTenure::create(['type' => 'Posesión sin título']);
        HousingTenure::create(['type' => 'Propiedad colectiva']);
        HousingTenure::create(['type' => 'Otra situación']);
    }
}
