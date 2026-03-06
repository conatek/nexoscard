<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collaborator_medical_examinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collaborator_id');
            $table->unsignedBigInteger('examination_type_id');
            $table->date('examination_date');
            $table->string('result_public_id')->nullable();
            $table->string('result_url')->nullable();
            $table->string('observations')->nullable();

            $table->foreign('collaborator_id')->references('id')->on('collaborators')->onDelete('cascade');
            $table->foreign('examination_type_id')->references('id')->on('medical_examination_types')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collaborator_medical_examinations');
    }
};
