<?php

namespace Database\Seeders;

use App\Models\SexType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SexTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SexType::create(['name' => 'Femenino']);
        SexType::create(['name' => 'Masculino']);
    }
}
