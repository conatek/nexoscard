<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collaborator_contracts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('collaborator_id')->unsigned();
            $table->bigInteger('position_id')->unsigned();
            $table->integer('salary')->unsigned();
            $table->bigInteger('contract_type_id')->unsigned();
            $table->date('contract_start_date');
            $table->date('contract_end_date')->nullable();
            $table->date('test_period_end_date');
            $table->string('corporate_email')->nullable();
            $table->string('corporate_cellphone')->nullable();
            $table->bigInteger('bank_id')->unsigned();
            $table->string('bank_account')->nullable();
            $table->bigInteger('eps_id')->unsigned();
            $table->bigInteger('afp_pension_id')->unsigned();
            $table->bigInteger('afp_saving_id')->unsigned();
            $table->bigInteger('arl_id')->unsigned();
            $table->bigInteger('ccf_id')->unsigned();

            $table->foreign('collaborator_id')->references('id')->on('collaborators');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreign('contract_type_id')->references('id')->on('contract_types');
            $table->foreign('bank_id')->references('id')->on('bank_types');
            $table->foreign('eps_id')->references('id')->on('eps_types');
            $table->foreign('afp_pension_id')->references('id')->on('afp_types');
            $table->foreign('afp_saving_id')->references('id')->on('afp_types');
            $table->foreign('arl_id')->references('id')->on('arl_types');
            $table->foreign('ccf_id')->references('id')->on('ccf_types');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collaborator_contracts');
    }
};
