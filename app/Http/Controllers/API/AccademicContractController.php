<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\semester;
use App\Models\activated_contract;
use App\Models\academicyear_semester_contract;
use App\Models\scholar;
use App\Models\User;
use DB;

class AccademicContractController extends Controller
{

    public function confirmPassword(Request $request){

        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)){
            throw ValidationException::withMessages([
                'message' => ['Incorrect password.'],
            ]);
        }

        return response()->json(['message'=> true], 200);

    }

    public function getAcademicContractDetails()
    {
        try {

            $activated_contract_details = activated_contract::with('academicYearSemester:asc_id,semester,academic_year')
                    ->select('activated_contract_id', 'ascId', 'created_at', 'updated_at', 'contract_state')
                    ->first();
            if ($activated_contract_details) {
                return [$activated_contract_details];
            }
            return response()->json(['message' => 'Contract not set.'], 500);

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function setContract(Request $request)
    {
        
        try {

            $state = academicyear_semester_contract::find($request->ascId);

            if ($state->state != 'Available') {
               return response()->json(['message' => 'Failed! Cannot set contract.'], 500);
            }

            $contract = activated_contract::first();

            if ($contract && $contract->contract_state == 'Closed') {

                DB::beginTransaction();
                
                $contract->ascId = $request->ascId;
                $contract->contract_state = "Open";
                $contract->save();

                academicyear_semester_contract::findOrFail($contract->ascId)->update(['state'=> 'Selected']);
                scholar::where('contract_status', 'Approved')->update(['contract_status'=> 'Pending']);
                scholar::where('scholar_status', 'NEW')->update(['scholar_status'=> 'OLD']);

                DB::commit();

                return response()->json(['message' => 'Contract opened'], 200);

            }

            if (!$contract) {

                DB::beginTransaction();

                $c = new activated_contract;
                $c->ascId = $request->ascId;
                $c->contract_state = "Open";
                $c->save();

                academicyear_semester_contract::findOrFail($request->ascId)->update(['state'=> 'Selected']);

                DB::commit();

                return response()->json(['message' => $c], 200);
            }

            

            return response()->json(['message' => 'Failed!. Contract already opened.'] , 500);

        } catch (Exception $e) {
            DB::roolback();
            throw $e;
        }
        
    }

    public function closeContract()
    {

        DB::beginTransaction();
        try {

            $contract = activated_contract::first();

            if ($contract && $contract->contract_state == 'Open') {

                $contract->contract_state = 'Closed';
                $contract->save();

                academicyear_semester_contract::findOrFail($contract->ascId)->update(['state'=> 'Closed']);
                scholar::where('contract_status', 'Pre-Approved')->update(['contract_status'=> 'Approved']);
                scholar::where('contract_status', 'Pending')->update(['contract_status'=> 'In-Active']);

                DB::commit();

                return response()->json(['message' => 'Contract Closed'] , 200);

            }

            return response()->json(['message' => 'Failed. Contract already closed'] , 500);

        } catch (Exception $e) {
            DB::roolback();
            throw $e;
        }

    }

    public function openContract()
    {

        DB::beginTransaction();
        try {

            $contract = activated_contract::first();

            if ($contract->contract_state == 'Closed') { 

                $contract->update(['contract_state'=> 'Open']);
                academicyear_semester_contract::findOrFail($contract->ascId)->update(['state'=> 'Selected']);

                DB::commit();

                return response()->json(['message' => 'Contract opened'] , 200);

            }

            return response()->json(['message' => 'Contract already opened'] , 500); 

        } catch (Exception $e) {
            DB::roolback();
            throw $e;
        }

    }

}
