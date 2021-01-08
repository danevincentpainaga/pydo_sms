<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\father_detail;
use App\Models\mother_detail;

class ScholarParentsController extends Controller
{

	public function getMotherList(Request $request){
		$result = mother_detail::where('m_lastname', 'LIKE', "%{$request->searched}%")->get();
		return response()->json($result, 200);	
	}

	public function getFatherList(Request $request){
		$result = father_detail::where('f_lastname', 'LIKE', "%{$request->searched}%")->get();
		return response()->json($result, 200);	
	}
}
