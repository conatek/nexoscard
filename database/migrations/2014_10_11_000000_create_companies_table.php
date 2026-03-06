<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->unsignedBigInteger('company_type_id');
            $table->unsignedBigInteger('identification_type_id');
            $table->string('identification_number');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('city_id');
            $table->string('address')->nullable();
            $table->unsignedBigInteger('industry_type_id');
            $table->string('size');
            $table->date('founded_at')->nullable();
            $table->boolean('is_active')->default(1);
            $table->text('description')->nullable();
            $table->string('logo_public_id')->nullable();
            $table->string('logo_url')->nullable();

            $table->string('contact_name')->nullable();
            $table->string('contact_first_surname')->nullable();
            $table->string('contact_second_surname')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('cellphone')->nullable();

            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('x_twitter')->nullable();
            $table->string('youtube')->nullable();

            $table->foreign('company_type_id')->references('id')->on('company_types');
            $table->foreign('identification_type_id')->references('id')->on('document_types');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('industry_type_id')->references('id')->on('industries');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
