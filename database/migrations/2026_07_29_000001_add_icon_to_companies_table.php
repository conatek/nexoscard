<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Icono para el acceso directo de las tarjetas públicas.
 *
 * Cuando un visitante guarda una tarjeta en la pantalla de inicio, el sistema toma el
 * icono del head de la página. Sin un campo propio solo quedaba el de NexosCard, igual
 * para todos los clientes. Es una imagen distinta del logotipo: tiene que ser cuadrada
 * y legible a 192 px, y un logo apaisado no lo es.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('icon_path')->nullable()->after('logo_public_id');
            $table->string('icon_public_id')->nullable()->after('icon_path');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['icon_path', 'icon_public_id']);
        });
    }
};
