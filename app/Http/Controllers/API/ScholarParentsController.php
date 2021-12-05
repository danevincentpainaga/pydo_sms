<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\scholar;
use App\Http\Controllers\API\ScholarValidations;

class ScholarParentsController extends Controller
{
	private $sv;

	public function __construct(){
		$this->sv = new ScholarValidations();
	}

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

			$scholar = scholar::findOrFail($request->scholar_id);

			if(!$this->checkIfScholarExist($scholar, $request)){
				$scholar->father_details = $this->sv->makeNullEmptyString($request->father_details);
				$scholar->mother_details = $this->sv->makeNullEmptyString($request->mother_details);
				$scholar->save();

				return response()->json(['father_details'=> $scholar->father_details, 'mother_details'=> $scholar->mother_details, 'updated_at'=> $scholar->updated_at], 200);				
			}

			return response()->json(['exist'=> true, 'message'=> 'Scholar already exist'], 422);

		} catch (Exception $e) {
			throw $e;
		}

	}

	private function checkIfScholarExist($scholar, $request){
		
		$rm = $request->mother_details;
		$sm = $scholar->mother_details;

		if($rm['firstname'].$rm['middlename'].$rm['maiden_name'] != $sm['firstname'].$sm['middlename'].$sm['maiden_name']){
			return scholar::where([
					'lastname' => $this->sv->trimAndAcceptLettersSpacesOnly($scholar->lastname),
					'firstname' => $this->sv->trimAndAcceptLettersSpacesOnly($scholar->firstname),
					'middlename' => $this->sv->trimAndAcceptLettersSpacesOnly($scholar->middlename),
					'suffix' => $scholar->suffix
			])
			->whereJsonContains('mother_details->maiden_name', $request->mother_details['maiden_name'])
			->whereJsonContains('mother_details->middlename', $request->mother_details['middlename'])
			->whereJsonContains('mother_details->firstname', $request->mother_details['firstname'])
			->count();
		}

		return "";

	}

	// private function makeNullEmptyString($arr){
 //    	foreach ($arr as $key => $value) {
 //    		if(!$value){
 //    			$arr[$key] = "";
 //    		}
 //    	}
 //    	return $arr;
	// }

}
