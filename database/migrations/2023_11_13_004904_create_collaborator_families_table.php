<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collaborator_families', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collaborator_id');
            $table->string('name');
            $table->string('first_surname');
            $table->string('second_surname')->nullable();
            $table->unsignedBigInteger('relationship_id');
            $table->unsignedBigInteger('sex_type_id');
            $table->unsignedBigInteger('occupation_id');
            $table->date('birth_date')->nullable();

            $table->foreign('collaborator_id')->references('id')->on('collaborators')->onDelete('cascade');
            $table->foreign('relationship_id')->references('id')->on('relationships')->onDelete('cascade');
            $table->foreign('sex_type_id')->references('id')->on('sex_types')->onDelete('cascade');
            $table->foreign('occupation_id')->references('id')->on('occupations')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collaborator_families');
    }
};
