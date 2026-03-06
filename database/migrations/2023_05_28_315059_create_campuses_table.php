<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('province_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->string('name');
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('campuses');
    }
};
