<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicyearSemesterContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academicyear_semester_contracts', function (Blueprint $table) {
            $table->bigIncrements('asc_id');
            $table->bigInteger('semesterId')->unsigned();
            $table->string('academic_year');
            $table->foreign('semesterId')->references('semester_id')->on('semesters');
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
        Schema::dropIfExists('academicyear_semester_contracts');
    }
}
