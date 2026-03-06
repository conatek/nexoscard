<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScholarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Scholarship::create(['name' => 'Primaria']);
        Scholarship::create(['name' => 'Bachiller']);
        Scholarship::create(['name' => 'Técnico']);
        Scholarship::create(['name' => 'Tecnólogo']);
        Scholarship::create(['name' => 'Profesional']);
        Scholarship::create(['name' => 'Especialista']);
        Scholarship::create(['name' => 'Magister']);
        Scholarship::create(['name' => 'Doctor']);
    }
}
