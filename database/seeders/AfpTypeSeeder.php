<?php

namespace Database\Seeders;

use App\Models\AfpType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AfpTypeSeeder extends Seeder
{
    public function run()
    {
        AfpType::create(['name' => 'Colfondos Pensiones y Cesantías']);
        AfpType::create(['name' => 'Colpensiones']);
        AfpType::create(['name' => 'Old Mutual']);
        AfpType::create(['name' => 'Porvenir']);
        AfpType::create(['name' => 'Protección']);
    }
}
