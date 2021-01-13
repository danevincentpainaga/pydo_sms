<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\semester;
use App\Models\activated_contract;
use App\Models\academicyear_semester_contract;

class AccademicContractController extends Controller
{
    public function getAcademicContractDetails(){
    	$contract_id = activated_contract::find(1)->first();
    	return academicyear_semester_contract::where('asc_id', $contract_id->ascId)->with('semester')->get();
    }
}
