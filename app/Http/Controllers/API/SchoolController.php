<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\school;

class SchoolController extends Controller
{
    public function getSearchedSchool($searched = ""){
    	return school::where('school_name', 'LIKE', "{$searched}%")->get();
    }

    public function getListOfSchool(Request $request){
    	return school::where('school_name', 'LIKE', "%{$request->searched_school}%")->with('province')->get();
    }
}
