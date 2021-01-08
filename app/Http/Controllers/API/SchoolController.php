<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\school;

class SchoolController extends Controller
{
    public function getSchools($searched){
    	return school::where('school_name', 'like', $searched .'%')->get();
    }
}
