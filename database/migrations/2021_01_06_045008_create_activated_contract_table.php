<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivatedContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activated_contract', function (Blueprint $table) {
            $table->bigIncrements('activated_contract_id');
            $table->bigInteger('ascId')->unsigned();
            $table->bigInteger('old_ascId')->unsigned();
            $table->string('contract_state')->nullable();
            $table->foreign('old_ascId')->references('asc_id')->on('academicyear_semester_contracts');
            $table->foreign('ascId')->references('asc_id')->on('academicyear_semester_contracts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activated_contract');
    }
}
