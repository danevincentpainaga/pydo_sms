<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotherDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mother_details', function (Blueprint $table) {
            $table->bigIncrements('mother_details_id');
            $table->string('m_lastname');
            $table->string('m_firstname');
            $table->string('m_middlename')->nullable();
            $table->string('m_occupation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mother_details');
    }
}
