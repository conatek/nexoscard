<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // La tabla companies tiene FKs huérfanas (document_types fue eliminada).
        // Deshabilitamos los checks para poder modificar la tabla sin que MySQL valide
        // restricciones rotas de migraciones anteriores.
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('companies', 'name')) {
                $table->string('name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('companies', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('companies', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('companies', 'address_notes')) {
                $table->string('address_notes')->nullable()->after('address');
            }
            if (!Schema::hasColumn('companies', 'twitter')) {
                $table->string('twitter')->nullable()->after('x_twitter');
            }
            if (!Schema::hasColumn('companies', 'design_settings')) {
                $table->json('design_settings')->nullable()->after('description');
            }
        });

        // Agregar FK de user_id si no tiene su constraint todavía
        $fks = array_column(
            DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                        WHERE TABLE_SCHEMA = DATABASE()
                        AND TABLE_NAME = 'companies'
                        AND CONSTRAINT_NAME = 'companies_user_id_foreign'"),
            'CONSTRAINT_NAME'
        );
        if (empty($fks)) {
            Schema::table('companies', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(array_filter(
                ['user_id', 'name', 'slug', 'logo_path', 'address_notes', 'twitter', 'design_settings'],
                fn($col) => Schema::hasColumn('companies', $col)
            ));
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
