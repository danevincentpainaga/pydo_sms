<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\province;

class ProvinceController extends Controller
{
    public function getProvinces(){
    	return province::all();
    }
}
