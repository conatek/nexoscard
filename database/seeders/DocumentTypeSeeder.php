<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run()
    {
        DocumentType::create(['type' => 'Cédula de ciudadanía']);
        DocumentType::create(['type' => 'Cédula de extranjería']);
        DocumentType::create(['type' => 'NIT']);
        DocumentType::create(['type' => 'NUIP']);
        DocumentType::create(['type' => 'Pasaporte']);
        DocumentType::create(['type' => 'Tarjeta de identidad']);
    }
}
