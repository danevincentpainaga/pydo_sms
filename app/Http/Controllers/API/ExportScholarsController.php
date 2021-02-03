<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\scholar;
use DB;

class ExportScholarsController extends validateUserCredentials
{

	public function getScholarsToExport(Request $request)
	{
		$accessed_degree = json_decode(Auth::user()->degree_access);

		if ($this->validateDegree($request->degree, $accessed_degree)) {

			$municipalities_access = $this->filteredMunicipality($request->municipality);
			
			if ($municipalities_access) {

				return scholar::with(['address', 'school', 'academicyear_semester_contract'])
				->whereHas('address', function($query) use ($municipalities_access, $request){

					if ($municipalities_access[0] != "*") {
						$query->whereIn('municipality', $municipalities_access );
					}

					$query->where('address', 'LIKE',"%{$request->address}%");
				})
				->whereHas('academicyear_semester_contract', function($query) use ($request){

					$query->where('semester', 'LIKE', "{$request->semester}%");
					$query->where('academic_year', 'LIKE', "{$request->academic_year}%");

				})
				->whereIn('degree', $accessed_degree)
				->where(DB::raw('CONCAT(lastname," ",firstname, " ",middlename)'), 'LIKE', "{$request->searched_name}%")
				->where('age', 'LIKE', $request->age)
				->where('gender', 'LIKE', $request->gender)
				->where('scholar_status', 'LIKE',  $request->scholar_status)
				->where('contract_status', 'LIKE',  $request->contract_status)
				->where('course_section', 'LIKE', "{$request->course_section}%")
				->where('student_id_number', 'LIKE', "{$request->student_id_number}%")
				->where('year_level', 'LIKE', $request->year_level)
				->where('IP', 'LIKE', $request->IP)
				->where('schoolId', 'LIKE', $request->schoolId)
				->where('degree', 'LIKE', $request->degree)
				->orderBy('lastname')
				->get();

			}

			return response()->json(['message'=> 'UnAuthorized. No municipality access!'], 403);	
		};

		return response()->json(['message'=> 'UnAuthorized!'], 403);
	}

}
