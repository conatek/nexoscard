<?php

namespace Database\Seeders;

use App\Models\MedicalExaminationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalExaminationTypeSeeder extends Seeder
{
    public function run()
    {
        MedicalExaminationType::create(['name' => 'Preocupacional (de preingreso)']);
        MedicalExaminationType::create(['name' => 'Ocupacional periódica (programada)']);
        MedicalExaminationType::create(['name' => 'Ocupacional periódica (por cambio de ocupación)']);
        MedicalExaminationType::create(['name' => 'Postocupacional (de egreso)']);
    }
}
