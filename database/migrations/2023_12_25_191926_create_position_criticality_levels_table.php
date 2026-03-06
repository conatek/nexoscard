<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('position_criticality_levels', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->mediumText('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('position_criticality_levels');
    }
};
