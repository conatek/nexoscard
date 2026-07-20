<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Registro de qué recordatorios ya se enviaron por suscripción.
 *
 * Hasta ahora el comando no tenía dedupe y funcionaba solo porque el scheduler corre una
 * vez al día. Con dos recordatorios configurados (3 y 1 día), cualquier ejecución
 * repetida — un reintento, una corrida manual — duplicaba correos al cliente.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->json('reminders_sent')->nullable()->after('billing_period');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('reminders_sent');
        });
    }
};
