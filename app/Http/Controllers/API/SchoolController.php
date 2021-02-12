<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\school;

class SchoolController extends Controller
{

    public function getListOfSchool(Request $request)
    {
    	return school::where('school_name', 'LIKE', "%{$request->searched_school}%")->limit(7)->get();
    }

     public function saveSchoolDetails(Request $request)
     {

        $request->validate([
            'school_name' => 'required',
        ]);

    	return school::create($request->all());

    }

    public function updateSchoolDetails(Request $request)
    {

        $request->validate([
            'school_id' => 'required',
            'school_name' => 'required',
        ]);

        $school = school::find($request->school_id);
        $school->school_name = $request->school_name;
        $school->save();
        
        return $request->all();
    }

}
