<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\scholar;

class ScholarValidations Extends Controller
{
	// public function findScholarIfExist($scholar, $request){
	// 		return scholar::where([
	// 				'lastname' => $this->trimAndAcceptLettersSpacesOnly($request->lastname),
	// 				'firstname' => $this->trimAndAcceptLettersSpacesOnly($request->firstname),
	// 				'middlename' => $this->trimAndAcceptLettersSpacesOnly($request->middlename),
	// 				'suffix' => $request->suffix
	// 		])
	// 		->whereJsonContains('mother_details->maiden_name', $scholar->mother_details['maiden_name'])
	// 		->whereJsonContains('mother_details->middlename', $scholar->mother_details['middlename'])
	// 		->whereJsonContains('mother_details->firstname', $scholar->mother_details['firstname'])
	// 		->orWhere(function ($query) use ($request) {
	// 			$query->where([
	// 				'lastname' => $this->trimAndAcceptLettersSpacesOnly($request->lastname),
	// 				'firstname' => $this->trimAndAcceptLettersSpacesOnly($request->firstname),
	// 				'middlename' => $this->trimAndAcceptLettersSpacesOnly($request->middlename),
	// 				'suffix' => $request->suffix,
	// 				'student_id_number' => $request->student_id_number
	// 			]);
	// 		})
	// 		->count();
	// }

	// public function checkScholarIfExist($scholar, $request){
	// 	$validate = false;
	// 	$scholarExist = "";
	// 	// $rm = $request->mother_details;
	// 	// $sm = $scholar->mother_details;

	// 	if($scholar->firstname.$scholar->middlename.$scholar->lastname != $request->firstname.$request->middlename.$request->lastname || $scholar->student_id_number != $request->student_id_number){
	// 		$validate = true;
	// 	}

	// 	// if($scholar->student_id_number != $request->student_id_number){
	// 	// 	$validate = true;
	// 	// }

	// 	// if($rm['firstname'].$rm['middlename'].$rm['maiden_name'] != $sm['firstname'].$sm['middlename'].$sm['maiden_name']){
	// 	// 	$validate = true;
	// 	// }

	// 	if($validate){
	// 		$scholarExist = $this->findScholarIfExist($scholar, $request);
	// 	}

	// 	return $scholarExist;
	// }
	public function makeNullEmptyString($arr){
    	foreach ($arr as $key => $value) {
    		if(!$value){
    			$arr[$key] = "";
    		}
    	}
    	return $arr;
	}
	
    public function trimAndAcceptLettersSpacesOnly($value){
		return trim(preg_replace('/[^a-z\s]/i', '', $value));
    }
}
