<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collaborator_academic_achievements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collaborator_id');
            $table->unsignedBigInteger('achievement_type_id');
            $table->string('degree');
            $table->string('institution')->nullable();
            $table->date('end_date')->nullable();
            $table->string('certificate_public_id')->nullable();
            $table->string('certificate_url')->nullable();

            $table->foreign('collaborator_id')->references('id')->on('collaborators')->onDelete('cascade');
            $table->foreign('achievement_type_id')->references('id')->on('academic_achievement_types')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collaborator_academic_achievements');
    }
};
