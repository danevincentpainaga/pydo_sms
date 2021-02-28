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

     public function storeSchoolDetails(Request $request)
     {

        try {

            $request->validate([
                'school_name' => 'required',
            ]);


            $schoolname = trim(preg_replace('/[^a-z-]/i', '', $request->school_name));

            $result = school::whereRaw("REPLACE(`school_name`, ' ', '') = ? ", $schoolname )->first();

            if ($result) {
                return response()->json(["message" => $request->school_name ." already exist"], 500);
            }

            return school::create([ 'school_name'=>  trim(preg_replace('/[^a-z\s-]/i', '',$request->school_name)) ]);

        } catch (Exception $e) {
            throw $e;
        }

    }

    public function updateSchoolDetails(Request $request)
    {

        $request->validate([
            'school_id' => 'required',
            'school_name' => 'required',
        ]);

        $school = school::findOrFail($request->school_id);
        $school->school_name = $request->school_name;
        $school->save();
        
        return $request->all();
    }

}
