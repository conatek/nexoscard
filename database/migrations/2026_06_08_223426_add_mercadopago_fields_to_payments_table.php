<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('mercadopago_payment_id')->nullable()->after('payu_reference_code');
            $table->string('mercadopago_preference_id')->nullable()->after('mercadopago_payment_id');
            $table->string('mercadopago_order_id')->nullable()->after('mercadopago_preference_id');

            $table->index('mercadopago_payment_id');
            $table->index('mercadopago_preference_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['mercadopago_payment_id']);
            $table->dropIndex(['mercadopago_preference_id']);
            $table->dropColumn(['mercadopago_payment_id', 'mercadopago_preference_id', 'mercadopago_order_id']);
        });
    }
};
