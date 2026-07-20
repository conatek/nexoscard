<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * El negocio pasa de 3 planes (mensual/anual) a un único producto anual con precio
 * de oferta: "Presencia Digital", COP $69.900 normal y $39.900 en oferta.
 *
 * `price_yearly` pasa a llamarse `price_regular` (el precio tachado) y `price_monthly`
 * desaparece: dejar dos columnas de precio vivas es justo la ambigüedad que produce
 * cobros por el monto equivocado.
 */
return new class extends Migration
{
    public function up(): void
    {
        // El rename va en su propia llamada: mezclarlo con drop/add en un mismo
        // Blueprint da problemas según el driver.
        Schema::table('plans', function (Blueprint $table) {
            $table->renameColumn('price_yearly', 'price_regular');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('price_monthly');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('offer_price', 10, 2)->nullable()->after('price_regular');
            $table->dateTime('offer_ends_at')->nullable()->after('offer_price');
            $table->string('billing_period', 10)->default('yearly')->after('offer_ends_at');
            $table->boolean('is_default')->default(false)->after('is_active');

            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropIndex(['is_default']);
            $table->dropColumn(['offer_price', 'offer_ends_at', 'billing_period', 'is_default']);
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('price_monthly', 10, 2)->default(0)->after('display_name');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->renameColumn('price_regular', 'price_yearly');
        });
    }
};
