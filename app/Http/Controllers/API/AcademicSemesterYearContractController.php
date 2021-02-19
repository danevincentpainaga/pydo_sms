<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateAcademiSemesterYearContractRequest;
use App\Models\academicyear_semester_contract;

class AcademicSemesterYearContractController extends Controller
{
    public function getAcademicYearList()
    {
    	return academicyear_semester_contract::all();
    }

    public function saveAcademicYearList(ValidateAcademiSemesterYearContractRequest $request)
    {
        try {
            
            $asc = academicyear_semester_contract::whereIn('state', ['Available', 'Selected'])->count();

            if ($asc > 0) {
               return response()->json(['message'=> 'Failed! Cannot create more than one contract.'], 500);
            }

            academicyear_semester_contract::create(
                [
                    'semester'=> $request->semester,
                    'academic_year'=> $request->academic_year,
                    'state'=> 'Available',
                    'undergraduate_amount'=> $request->undergraduate_amount,
                    'masteral_doctorate_amount'=> $request->masteral_doctorate_amount,
                ]
            );

            return response()->json(['message'=> 'Update successful!'], 200);
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateAcademicYearList(ValidateAcademiSemesterYearContractRequest $request)
    {
        try {

            $ays_details = academicyear_semester_contract::find($request->asc_id);
            $ays_details->semester = $request->semester;
            $ays_details->academic_year = $request->academic_year;
            $ays_details->undergraduate_amount = $request->undergraduate_amount;
            $ays_details->masteral_doctorate_amount = $request->masteral_doctorate_amount;
            $ays_details->save();

            return response()->json(['message'=> 'Update successful!'], 200);

        } catch (Exception $e) {
            throw $e;
        }
    }
    
}
