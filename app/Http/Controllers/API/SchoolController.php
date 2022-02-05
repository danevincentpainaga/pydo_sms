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


            if ($this->isExist($request->school_name)) {
                return response()->json(["message" => $this->trimSchoolName($request->school_name) ." already exist"], 409);
            }

            return school::create([
                'school_name'=>  $this->trimSchoolName($request->school_name)
            ]);

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

        if ($this->isExist($request->school_name)) {
            return response()->json(["message" => $this->trimSchoolName($request->school_name) ." already exist"], 409);
        }

        school::where('school_id', $request->school_id)->update([ 'school_name'=> $this->trimSchoolName($request->school_name) ]);
        
        return response('Success', 200);
    }

    private function isExist($school_name){
        $schoolname = trim(preg_replace('/[^a-z\.\-\']/i', '', $school_name), '\'-." "');

        return school::whereRaw("REPLACE(`school_name`, ' ', '') = ? ", $schoolname )->first();
    }

    private function trimSchoolName($school_name){

        $schoolname = preg_replace(array('/\s+/', '/\s+\'/', '/\'\s+/'), array(" ", "'", "'"), $school_name);

        return trim(preg_replace('/[^a-z\s\.\-\']/i', '', $schoolname), '\'-." "');
    }

}
