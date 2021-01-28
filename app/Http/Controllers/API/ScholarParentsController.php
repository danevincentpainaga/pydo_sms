<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\scholar;

class ScholarParentsController extends Controller
{

	public function getMotherList(Request $request){
		$result = scholar::where('mother_details->lastname', 'LIKE', "{$request->searched}%")->pluck('mother_details');
		return response()->json($result, 200);	
	}

	public function getFatherList(Request $request){
		$result = scholar::where('father_details->lastname', 'LIKE', "{$request->searched}%")->pluck('father_details');
		return response()->json($result, 200);	
	}
}
