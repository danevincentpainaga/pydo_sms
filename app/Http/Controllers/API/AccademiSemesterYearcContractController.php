<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\academicyear_semester_contract;

class AccademiSemesterYearcContractController extends Controller
{
    public function getAcademicYearList()
    {
    	return academicyear_semester_contract::all();
    }

    public function saveAcademicYearList(Request $request)
    {
        try {
            
            $asc = academicyear_semester_contract::where('state', 'Available')->count();

            if ($asc > 0) {
               return response()->json(['message'=> '1 Academic year and semester already available for contract.'], 500);
            }

            academicyear_semester_contract::create(
                [
                    'semester'=> $request->semester,
                    'academic_year'=> $request->academic_year,
                    'state'=> 'Available',
                    'amount'=> $request->amount,
                ]
            );

            return response()->json(['message'=> 'Update successful!'], 200);
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateAcademicYearList(Request $request)
    {
        try {

            $ays_details = academicyear_semester_contract::find($request->asc_id);
            $ays_details->semester = $request->semester;
            $ays_details->academic_year = $request->academic_year;
            $ays_details->amount = $request->amount;
            $ays_details->save();

            return response()->json(['message'=> 'Update successful!'], 200);

        } catch (Exception $e) {
            throw $e;
        }
    }
    
}
