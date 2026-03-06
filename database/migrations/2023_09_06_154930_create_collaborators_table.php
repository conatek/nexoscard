<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collaborators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('document_type_id');
            $table->string('document_number');
            $table->date('expedition_date')->nullable();
            // Pais de expedición
            $table->unsignedBigInteger('document_province_id');
            $table->unsignedBigInteger('document_city_id');
            $table->string('name');
            $table->string('first_surname');
            $table->string('second_surname')->nullable();
            $table->unsignedBigInteger('civil_status_type_id');
            $table->unsignedBigInteger('sex_type_id');
            $table->unsignedBigInteger('rh_type_id');
            $table->date('birth_date')->nullable();
            // País de nacimiento
            $table->unsignedBigInteger('birth_province_id');
            $table->unsignedBigInteger('birth_city_id');
            // País de residencia
            $table->unsignedBigInteger('residence_province_id');
            $table->unsignedBigInteger('residence_city_id');
            $table->string('address')->nullable();
            $table->unsignedBigInteger('housing_tenure_id');
            $table->unsignedBigInteger('stratum_type_id');
            $table->string('phone')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('email')->nullable();
            $table->string('image_public_id')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(1);
            $table->text('observations')->nullable();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('document_type_id')->references('id')->on('document_types');
            $table->foreign('document_province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('document_city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('civil_status_type_id')->references('id')->on('civil_status_types')->onDelete('cascade');
            $table->foreign('sex_type_id')->references('id')->on('sex_types')->onDelete('cascade');
            $table->foreign('rh_type_id')->references('id')->on('rh_types')->onDelete('cascade');
            $table->foreign('birth_province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('birth_city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('residence_province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('residence_city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('housing_tenure_id')->references('id')->on('housing_tenures')->onDelete('cascade');
            $table->foreign('stratum_type_id')->references('id')->on('social_strata')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collaborators');
    }
};
