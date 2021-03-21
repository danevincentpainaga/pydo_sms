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
        return course::where('course', 'LIKE', "%{$request->searched}%")->whereIn('course_degree', json_decode($request->degree))->limit(3)->get();
    }

    public function getCoursesList(Request $request){
        return course::where('course', 'LIKE', "%{$request->searched}%")->where('course_degree', 'LIKE', $request->degree)->paginate(15);
    }

    public function storeCourse(Request $request){

        $request->validate([
            'course' => 'required',
            'course_degree' => 'required',
        ]);


        $course = trim(preg_replace('/[^a-z]/i', '', $request->course));

        $result = course::whereRaw("REPLACE(`course`, ' ', '') = ? ", $course )->first();

        if ($result) {
            return response()->json(["message" => $request->course ." already exist"], 500);
        }

        return course::create([
            'course'=>  trim(preg_replace('/[^a-z\s]/i', '', $request->course)),
            'course_degree' => $request->course_degree
        ]);

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
