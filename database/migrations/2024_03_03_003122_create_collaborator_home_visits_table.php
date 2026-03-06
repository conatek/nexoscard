<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('collaborator_home_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collaborator_id');
            $table->unsignedBigInteger('home_visit_type_id');
            $table->date('visit_date');
            $table->date('next_visit_date')->nullable();
            $table->string('report_public_id')->nullable();
            $table->string('report_url')->nullable();
            $table->string('observations')->nullable();

            $table->foreign('collaborator_id')->references('id')->on('collaborators')->onDelete('cascade');
            $table->foreign('home_visit_type_id')->references('id')->on('home_visit_types')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collaborator_home_visits');
    }
};
