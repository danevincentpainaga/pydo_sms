<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateAcademiSemesterYearContractRequest;
use App\Models\academicyear_semester_contract;
use DB;

class AcademicSemesterYearContractController extends Controller
{
    public function getAcademicYearList()
    {
    	return academicyear_semester_contract::all();
    }

    public function storeAcademicYearList(ValidateAcademiSemesterYearContractRequest $request)
    {
        try {
            
            DB::beginTransaction();

            $asc = academicyear_semester_contract::whereIn('state', ['Available', 'Selected'])->count();

            if ($asc > 0) {
               return response()->json(['message'=> 'Failed! Cannot create more than one contract.'], 403);
            }

            $res = academicyear_semester_contract::where(['academic_year'=> $request->academic_year, 'semester'=> $request->academic_year])->count();

            if($res > 0){
                return response()->json(['message'=> 'Failed! Academic year / semester already exist'], 403);
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

            DB::commit();

            return response()->json(['message'=> 'Update successful!'], 200);
            
        } catch (Exception $e) {
            DB::roolback();
            throw $e;
        }
    }

    public function updateAcademicYearList(ValidateAcademiSemesterYearContractRequest $request)
    {
        try {

            $ays_details = academicyear_semester_contract::findOrFail($request->asc_id);
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
