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

        $request->validate([
            'firstname' => 'required',
            'initial' => 'required',
            'lastname' => 'required'
        ]);    	

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
				$governor->governor = json_encode($gov);
				$governor->save();
			}
			else{
				governor::create(['selected' => 'true', 'governor'=> json_encode($gov) ]);
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
