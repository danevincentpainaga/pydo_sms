<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class validateUserCredentials Extends Controller
{

	protected function filteredMunicipality($municipality){

		if (!$municipality || $municipality == "Municipality") {
			return Auth::user()->municipal_access;
		}

		return [$municipality];
	}

	protected function validateDegree($degree, $accessed_degree ){

		if (!$degree || in_array($degree, $accessed_degree)) return true;
		
	}

	protected function filterScholarDegree($accessedDegree){

		$degree_access = Auth::user()->degree_access;
		
		if ($accessedDegree) {
			if($degree_access[0] == "*") return $accessedDegree;

			return array_values(array_intersect($degree_access, $accessedDegree));
		}
			
		if($degree_access[0] == "*") return ["Undergraduate", "Masters", "Doctorate"];
		
		return $degree_access;

	}

}
