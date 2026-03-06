<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('position_risk_classes', function (Blueprint $table) {
            $table->id();
            $table->string('class');
            $table->string('description');
            $table->decimal('quote_percentage', 5, 3);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('position_risk_classes');
    }
};
