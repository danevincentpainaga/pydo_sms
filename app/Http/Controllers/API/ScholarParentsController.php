<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\scholar;

class ScholarParentsController extends Controller
{

	public function getMotherList(Request $request){
		$result = scholar::where('mother_details->maiden_name', 'LIKE', "{$request->searched}%")->limit(3)->pluck('mother_details');
		return response()->json($result, 200);
	}

	public function getFatherList(Request $request){
		$result = scholar::where('father_details->lastname', 'LIKE', "{$request->searched}%")->limit(3)->pluck('father_details');
		return response()->json($result, 200);
	}

	public function updateScholarParentsDetails(Request $request){

		try {

	        $request->validate([
	        	'scholar_id' => 'required',
	        	'father_details' => 'required',
	            'mother_details' => 'required',
	        ]);

			$s = scholar::findOrFail($request->scholar_id);
			$s->father_details = $request->father_details;
			$s->mother_details = $request->mother_details;
			$s->save();

			return response()->json(['father_details'=> $s->father_details, 'mother_details'=> $s->mother_details, 'updated_at'=> $s->updated_at], 200);	

		} catch (Exception $e) {
			throw $e;
		}

	}

}
