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

    public function saveCourse(Request $request){

        $request->validate([
            'course' => 'required',
        ]);

        return course::create($request->all());
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
