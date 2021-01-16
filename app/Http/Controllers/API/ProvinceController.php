<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\province;

class ProvinceController extends Controller
{
    public function getProvinces(Request $request){
    	return province::where('province_name', 'LIKE', "{$request->searched_province}%")->get();
    }
}
