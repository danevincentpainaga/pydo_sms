<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOldAscIdToActivatedContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activated_contract', function (Blueprint $table) {
            $table->bigInteger('old_ascId')->unsigned()->after('ascId');
            $table->foreign('old_ascId')->references('asc_id')->on('academicyear_semester_contracts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activated_contract', function (Blueprint $table) {
            //
        });
    }
}
