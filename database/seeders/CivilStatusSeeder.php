<?php

namespace Database\Seeders;

use App\Models\CivilStatusType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CivilStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CivilStatusType::create(['type' => 'Soltero']);
        CivilStatusType::create(['type' => 'Casado']);
        CivilStatusType::create(['type' => 'Unión de hecho']);
        CivilStatusType::create(['type' => 'Separado']);
        CivilStatusType::create(['type' => 'Divorciado']);
        CivilStatusType::create(['type' => 'Viudo']);
    }
}
