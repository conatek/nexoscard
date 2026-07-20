<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hasta ahora el ciclo vendido solo vivía en `payments.metadata`, y los tres sitios que
 * activan una suscripción parcheaban `current_period_end` a mano con `addYear()`. Al
 * persistirlo aquí, la renovación tiene una fuente de verdad.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('billing_period', 10)->nullable()->after('plan_id');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('billing_period');
        });
    }
};
