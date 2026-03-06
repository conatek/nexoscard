<?php

namespace Database\Seeders;

use App\Models\BankType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankTypeSeeder extends Seeder
{
    public function run()
    {
        BankType::create(['name' => 'Banco Agrario']);
        BankType::create(['name' => 'Banco AV Villas']);
        BankType::create(['name' => 'Banco Caja Social']);
        BankType::create(['name' => 'Banco Cooperativo Coopcentral']);
        BankType::create(['name' => 'Banco de Bogotá']);
        BankType::create(['name' => 'Banco de Occidente']);
        BankType::create(['name' => 'Banco Falabella']);
        BankType::create(['name' => 'Banco Finandina']);
        BankType::create(['name' => 'Banco Itaú']);
        BankType::create(['name' => 'Banco Mundo Mujer']);
        BankType::create(['name' => 'Banco Pichincha']);
        BankType::create(['name' => 'Banco Popular']);
        BankType::create(['name' => 'Banco Union']);
        BankType::create(['name' => 'Banco W']);
        BankType::create(['name' => 'Bancamía']);
        BankType::create(['name' => 'Bancoldex']);
        BankType::create(['name' => 'Bancolombia']);
        BankType::create(['name' => 'Bancoomeva']);
        BankType::create(['name' => 'Ban100']);
        BankType::create(['name' => 'BBVA']);
        BankType::create(['name' => 'Davivienda']);
        BankType::create(['name' => 'GNB Sudameris']);
        BankType::create(['name' => 'Scotiabank Colpatria']);
    }
}
