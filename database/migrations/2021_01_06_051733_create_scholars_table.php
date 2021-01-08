<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholars', function (Blueprint $table) {
            $table->bigIncrements('scholar_id');
            $table->string('student_id_number');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->bigInteger('addressId')->unsigned();
            $table->foreign('addressId')->references('asc_id')->on('academicyear_semester_contracts');
            $table->string('date_of_birth');
            $table->string('age');
            $table->string('gender');
            $table->bigInteger('schoolId')->unsigned();
            $table->foreign('schoolId')->references('school_id')->on('schools');
            $table->string('IP');
            $table->bigInteger('fatherId')->unsigned();
            $table->foreign('fatherId')->references('father_details_id')->on('father_details');
            $table->bigInteger('motherId')->unsigned();
            $table->foreign('motherId')->references('mother_details_id')->on('mother_details');
            $table->string('photo');
            $table->string('degree');
            $table->string('scholar_status');
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
        Schema::dropIfExists('scholars');
    }
}
