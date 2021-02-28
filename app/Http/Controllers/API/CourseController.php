<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\course;
use DB;

class CourseController extends Controller
{
    public function getCourses(Request $request){
        return course::where('course', 'LIKE', "%{$request->searched}%")->get();
    }

    public function storeCourse(Request $request){

        $request->validate([
            'course' => 'required',
        ]);


        $course = trim(preg_replace('/[^a-z]/i', '', $request->course));

        $result = course::whereRaw("REPLACE(`course`, ' ', '') = ? ", $course )->first();

        if ($result) {
            return response()->json(["message" => $request->course ." already exist"], 500);
        }

        return course::create([ 'course'=>  trim(preg_replace('/[^a-z\s]/i', '',$request->course)) ]);

    }

    public function updateCourse(Request $request){
        
        $request->validate([
            'course_id' => 'required',
            'course' => 'required',
        ]);

        $course = course::findOrFail($request->course_id);
        $course->course = $request->course;
        $course->save();

        return $course;
    }

}
