<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\governor;
use DB;

class GovernorController extends Controller
{
    function updateGOvernor(Request $request)
    {

        $validator = Validator::make($request->all(), [
			'governor_id' => 'governor_id',
            'firstname' => 'required',
            'initial' => 'required',
            'lastname' => 'required'
        ]);    	

		if ($validator->fails()) {
			return response('Invalid inputs', 400);
		}
        // $gov = $request->all();
        
    	// foreach ($gov as $key => $value) {
    	// 	if(!$value){
    	// 		$gov[$key] = "";
    	// 	}
    	// }

    	try {
			DB::beginTransaction();
			$gov = governor::where('governor_id', $request->governor_id)->first();
			if($governor){
				$gov->firstname = $request->firstname;
				$gov->mi = $request->mi;
				$gov->lastname = $request->lastname;
				$gov->suffix = $request->suffix;
				$gov->save();
			}
			DB::commit();
			return response()->json(['message'=> 'Updated'], 200);
    	} catch (Exception $e) {
    		DB::roolback();
    		abort(500);
    	}
    }

    function getGovernorDetails(){
    	return governor::where('selected', 'true')->first();
    }

	function setGovernor(Request $request){
		try {
			DB::beginTransaction();
			$contract = activated_contract::first();
			if ($contract && $contract->contract_state == 'Open') {
				return response()->json(['message'=> 'Failed! Contract signing must be closed first.'], 422);
			}
			governor::create([
				'selected' => 'true',
				'firstname'=> $request->firstname,
				'mi'=> $request->mi,
				'lastname'=> $request->lastname,
				'suffix'=> $request->suffix
			]);
			governor::where('selected', 1)->update(['selected'=> 0]);	
			DB::commit();
			return response()->json(['message'=> 'Success'], 200);
			
		} catch (\Throwable $th) {
			DB::roolback();
			abort(500);
		}
	}
}
