<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class validateUserCredentials Extends Controller
{

	protected function filteredMunicipality($municipality){

		if (!$municipality || $municipality == "Municipality") {
			return json_decode(Auth::user()->municipal_access);
		}

		return [$municipality];
	}

	protected function validateDegree($degree, $accessed_degree ){

		if (!$degree || in_array($degree, $accessed_degree)) return true;
		
	}

	protected function filterScholarDegree($accessedDegree){
		if ($accessedDegree)

			return $accessed_degree = array_values(array_intersect(json_decode(Auth::user()->degree_access), $accessedDegree));

		return json_decode(Auth::user()->degree_access);
	}

}
