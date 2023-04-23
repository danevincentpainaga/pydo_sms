<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MunicipalitiesController extends Controller
{
    public function getMunicipalities(){

    	try {
    		
	    	$municipalities = array();
	    	$municipal_access = Auth::user()->municipal_access;

			if ($municipal_access[0] == "*") {

				return Storage::get('municipalities/municipalities.json');

			}

			foreach ($municipal_access as $key) {
				$municipalities[] =  array('municipality' => $key );
			}

			return $municipalities;

    	} catch (Exception $e) {
    		throw $e;
    	}
		
    }
}
