<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\academicyear_semester_contract;

class AccademiSemesterYearcContractController extends Controller
{
    public function getAcademicYearList(){
    	return academicyear_semester_contract::all();
    }

    public function saveAcademicYearList(Request $request){
    	return academicyear_semester_contract::create($request->all());
    }

    public function updateAcademicYearList(Request $request){
    	$ays_details = academicyear_semester_contract::find($request->asc_id);
    	$ays_details->semester = $request->semester;
    	$ays_details->academic_year = $request->academic_year;
    	$ays_details->save();
    	return $ays_details;
    }
    
}
