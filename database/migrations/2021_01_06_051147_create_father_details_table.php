<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFatherDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('father_details', function (Blueprint $table) {
            $table->bigIncrements('father_details_id');
            $table->string('f_lastname');
            $table->string('f_firstname');
            $table->string('f_middlename')->nullable();
            $table->string('f_occupation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('father_details');
    }
}
