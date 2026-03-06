<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collaborator_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collaborator_id');
            $table->unsignedBigInteger('document_type_id');
            $table->string('document_public_id')->nullable();
            $table->string('document_url')->nullable();
            $table->string('observations')->nullable();

            $table->foreign('collaborator_id')->references('id')->on('collaborators')->onDelete('cascade');
            $table->foreign('document_type_id')->references('id')->on('contractual_document_types')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collaborator_documents');
    }
};
