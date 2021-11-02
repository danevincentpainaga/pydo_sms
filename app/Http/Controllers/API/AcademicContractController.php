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
use App\Models\governor;
use DB;

class AcademicContractController extends Controller
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

            $activated_contract_details = activated_contract::with('academicYearSemester:asc_id,semester,academic_year,undergraduate_amount,masteral_doctorate_amount')
                    ->select('activated_contract_id', 'ascId', 'created_at', 'updated_at', 'contract_state')
                    ->first();

            if (!$activated_contract_details) return response()->json(['message' => 'Contract not set.'], 400);

            $governor = governor::where('selected', 'true')->first();
            $activated_contract_details['governor'] = $governor->governor;
            
            return [$activated_contract_details];

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function setContract(Request $request)
    {
        
        try {

            $request->validate([
                'ascId' => 'required',
            ]);

            $state = academicyear_semester_contract::findOrFail($request->ascId);

            if ($state->state != 'Available') {
               return response()->json(['message' => 'Failed! Cannot set contract.'], 400);
            }

            $governor = governor::where('selected', 'true')->first();

            if(!$governor) return response()->json(['message' => 'Please add Governor details'], 400);

            $contract = activated_contract::first();

            DB::beginTransaction();

            if (!$contract) {
                $c = new activated_contract();
                $c->ascId = $request->ascId;
                $c->contract_state = "Open";
                $c->save();
                $this->updateStatus();
                DB::commit();
                return response()->json(['message' => 'Contract succesfully setted.'], 200);
            }

            if ($contract->contract_state == 'Open') {
                return response()->json(['message' => 'Failed!. Contract already opened.'] , 400);
            }

            $contract->ascId = $request->ascId;
            $contract->contract_state = "Open";
            $contract->save();
            
            $this->updateStatus();
            academicyear_semester_contract::findOrFail($request->ascId)->update(['state'=> 'Selected']);

            DB::commit();

            return response()->json(['message' => 'Contract succesfully set.'], 200);


        } catch (Exception $e) {
            DB::roolback();
            throw $e;
        }
        
    }

    public function updateStatus(){
        scholar::where('contract_status', 'Pending')->update(['contract_status'=> 'In-Active']);
        scholar::where('contract_status', 'Approved')->update(['contract_status'=> 'Pending']);
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

                DB::commit();

                return response()->json(['message' => 'Contract Closed'] , 200);

            }

            return response()->json(['message' => 'Failed. Contract already closed'] , 400);

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

            return response()->json(['message' => 'Contract already opened'] , 400); 

        } catch (Exception $e) {
            DB::roolback();
            throw $e;
        }

    }

}
