<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\school;

class SchoolController extends Controller
{
    // public function getSearchedSchool($searched = "")
    // {
    // 	return school::where('school_name', 'LIKE', "{$searched}%")->get();
    // }

    public function getListOfSchool(Request $request)
    {
    	return school::where('school_name', 'LIKE', "{$request->searched_school}%")->take(7)->get();
    }

     public function saveSchoolDetails(Request $request)
     {

     	if ($request->has('school_name')) {

    		return school::create($request->all());
    		
     	}
    }

    public function updateSchoolDetails(Request $request)
    {
        if ($request->has(['school_id', 'school_name'])) {
            $school = school::find($request->school_id);
            $school->school_name = $request->school_name;
            $school->save();
        }
        
        return $request->all();
    }

}
