<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Occupation::create(['name' => 'Empleado']);
        Occupation::create(['name' => 'Desempleado']);
        Occupation::create(['name' => 'Estudiante']);
        Occupation::create(['name' => 'Pensionado']);
        Occupation::create(['name' => 'Amo(a) de casa']);
        Occupation::create(['name' => 'Otros']);
    }
}
