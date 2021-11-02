<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\governor;
use DB;

class GovernorController extends Controller
{
    function updateGOvernor(Request $request)
    {
    	$gov = $request->all();
    	foreach ($gov as $key => $value) {
    		if(!$value){
    			$gov[$key] = "";
    		}
    	}

    	DB::beginTransaction();

    	try {
			$governor = governor::where('selected', 'true')->first();
			
			if($governor){
				$governor->governor = $gov;
				$governor->save();
			}
			else{
				governor::create(['selected' => 'true', 'governor'=> $request->governor ]);
			}
			
			DB::commit();
			return response()->json(['message'=> 'Governor is updated'], 200);

    	} catch (Exception $e) {
    		DB::roolback();
    		throw $e;
    	}
    }

    function getGovernorDetails(){
    	return governor::where('selected', 'true')->first();
    }
}
