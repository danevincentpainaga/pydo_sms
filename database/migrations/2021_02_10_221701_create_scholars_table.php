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
            $table->bigInteger('addressId')->unsigned()->index();
            $table->foreign('addressId')->references('address_id')->on('addresses');
            $table->string('date_of_birth');
            $table->string('age');
            $table->string('gender');
            $table->bigInteger('schoolId')->unsigned();
            $table->foreign('schoolId')->references('school_id')->on('schools');
            $table->bigInteger('courseId')->unsigned();
            $table->foreign('courseId')->references('course_id')->on('courses');
            $table->string('section');
            $table->string('year_level');
            $table->string('IP');
            $table->json('father_details');
            $table->json('mother_details');
            $table->string('photo')->nullable();
            $table->string('degree');
            $table->string('scholar_status');
            $table->string('contract_status');
            $table->bigInteger('contract_id')->unsigned();
            $table->foreign('contract_id')->references('activated_contract_id')->on('activated_contract');
            $table->bigInteger('last_renewed')->unsigned();
            $table->foreign('last_renewed')->references('asc_id')->on('academicyear_semester_contracts');
            $table->bigInteger('sem_year_applied')->unsigned();
            $table->foreign('sem_year_applied')->references('asc_id')->on('academicyear_semester_contracts');
            $table->bigInteger('userId')->unsigned();
            $table->foreign('userId')->references('id')->on('users');
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
