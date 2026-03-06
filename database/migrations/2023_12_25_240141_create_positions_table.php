<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('area_id')->unsigned();
            $table->bigInteger('criticality_level_id')->unsigned();
            $table->bigInteger('risk_class_id')->unsigned();
            $table->string('name');
            $table->integer('estimated_salary')->unsigned()->nullable();
            $table->mediumText('description')->nullable();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('criticality_level_id')->references('id')->on('position_criticality_levels');
            $table->foreign('risk_class_id')->references('id')->on('position_risk_classes');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('positions');
    }
};
