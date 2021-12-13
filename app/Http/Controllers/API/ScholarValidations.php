<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\scholar;

class ScholarValidations Extends Controller
{
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
