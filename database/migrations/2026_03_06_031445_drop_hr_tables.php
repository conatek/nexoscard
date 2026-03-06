<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = [
            'collaborator_documents',
            'collaborator_home_visits',
            'collaborator_medical_examinations',
            'collaborator_academic_achievements',
            'collaborator_families',
            'collaborator_contracts',
            'collaborators',
            'positions',
            'position_risk_classes',
            'position_criticality_levels',
            'areas',
            'campuses',
            'contract_types',
            'medical_examination_types',
            'home_visit_types',
            'contractual_document_types',
            'occupations',
            'relationships',
            'housing_tenures',
            'ccf_types',
            'arl_types',
            'afp_types',
            'eps_types',
            'bank_types',
            'scholarships',
            'academic_achievement_types',
            'rh_types',
            'sex_types',
            'social_strata',
            'civil_status_types',
            'document_types',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No se recrea — las tablas HR fueron eliminadas intencionalmente
    }
};
