<?php

namespace Database\Seeders;

use App\Models\ContractType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractTypeSeeder extends Seeder
{
    public function run()
    {
        ContractType::create(['name' => 'Aprendizaje']);
        ContractType::create(['name' => 'Término Fijo']);
        ContractType::create(['name' => 'Término Indefinido']);
        ContractType::create(['name' => 'Obra Labor']);
    }
}
