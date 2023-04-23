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
            $table->string('student_id_number', 30);
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('suffix')->nullable();
            $table->bigInteger('addressId')->unsigned()->index();
            $table->foreign('addressId')->references('address_id')->on('addresses');
            $table->string('date_of_birth', 10);
            $table->string('age', 3);
            $table->string('gender');
            $table->bigInteger('schoolId')->unsigned();
            $table->foreign('schoolId')->references('school_id')->on('schools');
            $table->bigInteger('courseId')->unsigned();
            $table->foreign('courseId')->references('course_id')->on('courses');
            $table->string('section');
            $table->string('year_level', 20);
            $table->string('IP', 3);
            $table->text('father_details');
            $table->text('mother_details');
            $table->string('photo')->nullable();
            $table->string('degree', 20);
            $table->string('scholar_status', 3);
            $table->string('contract_status', 25);
            $table->string('civil_status', 15);
            $table->bigInteger('contract_id')->unsigned();
            $table->foreign('contract_id')->references('activated_contract_id')->on('activated_contract');
            $table->bigInteger('last_renewed')->unsigned();
            $table->foreign('last_renewed')->references('asc_id')->on('academicyear_semester_contracts');
            $table->bigInteger('sem_year_applied')->unsigned();
            $table->foreign('sem_year_applied')->references('asc_id')->on('academicyear_semester_contracts');
            $table->bigInteger('created_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->boolean('isActive')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
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
