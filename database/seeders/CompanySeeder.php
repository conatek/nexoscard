<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::create([
            'name' => 'CONATEK',
            'slug' => 'conatek',
        ]);

        Company::create([
            'name' => 'MUY HUMANO',
            'slug' => 'muy-humano',
        ]);
    }
}
