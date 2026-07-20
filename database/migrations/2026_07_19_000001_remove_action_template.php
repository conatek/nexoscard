<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Elimina la plantilla "Acción": Impulso pasa a ser la plantilla por defecto y las
 * empresas que seguían en 'action' se migran a 'impulso'.
 *
 * La customization guardada corresponde al schema de Acción (otras claves, y un
 * colorFondo blanco que dejaría la tarjeta ilegible), así que se reemplaza por los
 * valores por defecto de Impulso en lugar de arrastrarla.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('template_name')->default('impulso')->change();
        });

        DB::table('company_settings')
            ->where('template_name', 'action')
            ->update([
                'template_name' => 'impulso',
                'customization' => json_encode($this->defaultsFor('impulso')),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('template_name')->default('modern')->change();
        });

        // No se revierten las empresas migradas: la plantilla 'action' ya no existe.
    }

    /**
     * Extrae los valores por defecto del schema de una plantilla.
     */
    private function defaultsFor(string $template): array
    {
        $values = [];

        foreach (config("templates.schemas.{$template}", []) as $sectionKey => $section) {
            if (!is_array($section)) {
                continue;
            }

            $values[$sectionKey] = [];

            foreach ($section as $fieldKey => $field) {
                if (str_starts_with($fieldKey, '_')) {
                    continue;
                }
                if (is_array($field) && array_key_exists('value', $field)) {
                    $values[$sectionKey][$fieldKey] = $field['value'];
                }
            }
        }

        return $values;
    }
};
