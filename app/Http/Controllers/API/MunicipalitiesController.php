<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MunicipalitiesController extends Controller
{
    public function getMunicipalities(){

    	$municipalities = array();
    	$municipal_access = json_decode(Auth::user()->municipal_access);

		if ($municipal_access[0] == "*") {

			return Storage::get('municipalities/municipalities.json');

		}

		foreach ($municipal_access as $key) {
			$municipalities[] =  array('municipality' => $key );
		}

		return $municipalities;
		
    }
}
