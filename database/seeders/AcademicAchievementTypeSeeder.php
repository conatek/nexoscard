<?php

namespace Database\Seeders;

use App\Models\AcademicAchievementType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicAchievementTypeSeeder extends Seeder
{
    public function run()
    {
        AcademicAchievementType::create(['type' => 'Educación Básica - Primaria']);
        AcademicAchievementType::create(['type' => 'Educación Básica - Secundaria']);
        AcademicAchievementType::create(['type' => 'Educación Media - Bachillerato']);
        AcademicAchievementType::create(['type' => 'Pregrado - Nivel Técnico Profesional']);
        AcademicAchievementType::create(['type' => 'Pregrado - Nivel Tecnológico']);
        AcademicAchievementType::create(['type' => 'Pregrado - Nivel Profesional']);
        AcademicAchievementType::create(['type' => 'Posgrado - Especialización Técnica Profesional']);
        AcademicAchievementType::create(['type' => 'Posgrado - Especialización Tecnológica']);
        AcademicAchievementType::create(['type' => 'Posgrado - Especialización Profesional']);
        AcademicAchievementType::create(['type' => 'Posgrado - Magister']);
        AcademicAchievementType::create(['type' => 'Posgrado - Doctorado']);
    }
}
