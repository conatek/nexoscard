<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('campus_id')->unsigned();
            $table->bigInteger('leader_id')->unsigned();
            $table->string('name');
            $table->mediumText('description')->nullable();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('campus_id')->references('id')->on('campuses');
            $table->foreign('leader_id')->references('id')->on('collaborators');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('areas');
    }
};
