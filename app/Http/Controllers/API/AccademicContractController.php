<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\semester;
use App\Models\activated_contract;
use App\Models\academicyear_semester_contract;
use App\Models\scholar;

class AccademicContractController extends Controller
{
    public function getAcademicContractDetails(){
    	$contract_id = activated_contract::all()->first();
    	return academicyear_semester_contract::where('asc_id', $contract_id->ascId)->get();
    }

    public function setContract(Request $request){

    	$contract = activated_contract::all()->first();
    	
    	$contract->ascId = $request->ascId;
    	$contract->contract_state = "Started";
    	$contract->save();

    	scholar::where('contract_status', 'Approved')->update(['contract_status'=> 'Queued']);
    	scholar::where('contract_status', 'Pre-Approved')->update(['contract_status'=> 'Approved']);
    	scholar::where('contract_status', 'Pending')->update(['contract_status'=> 'In-Active']);
    	scholar::where('scholar_status', 'NEW')->update(['scholar_status'=> 'OLD']);


    	return $contract;
    }

    public function revertContract(Request $request){

    	$contract = activated_contract::all()->first();
    	
    	$contract->ascId = $request->ascId;
    	$contract->save();

    	scholar::where('contract_status', 'Pre-Approved')->update(['contract_status'=> 'Pending']);
    	scholar::where('contract_status', 'Approved')->update(['contract_status'=> 'Pre-Approved']);
    	scholar::where('contract_status', 'Queued')->update(['contract_status'=> 'Approved']);
    	scholar::where('scholar_status', 'OLD')->update(['scholar_status'=> 'NEW']);

    	return $contract;
    }



}
