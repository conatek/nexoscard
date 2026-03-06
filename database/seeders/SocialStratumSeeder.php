<?php

namespace Database\Seeders;

use App\Models\SocialStratum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialStratumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SocialStratum::create(['name' => 'Bajo-Bajo']);
        SocialStratum::create(['name' => 'Bajo']);
        SocialStratum::create(['name' => 'Medio-Bajo']);
        SocialStratum::create(['name' => 'Medio']);
        SocialStratum::create(['name' => 'Medio-Alto']);
        SocialStratum::create(['name' => 'Alto']);
    }
}
