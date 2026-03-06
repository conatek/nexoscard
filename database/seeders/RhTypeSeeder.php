<?php

namespace Database\Seeders;

use App\Models\RhType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RhTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RhType::create(['name' => 'O-']);
        RhType::create(['name' => 'O+']);
        RhType::create(['name' => 'A-']);
        RhType::create(['name' => 'A+']);
        RhType::create(['name' => 'B-']);
        RhType::create(['name' => 'B+']);
        RhType::create(['name' => 'AB-']);
        RhType::create(['name' => 'AB+']);
    }
}
