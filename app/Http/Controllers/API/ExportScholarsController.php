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
		$municipalities_access = $this->filteredMunicipality($request->municipality);
		
		$query = DB::table('scholars');
					if ($municipalities_access[0] != "*") {
						$query->whereIn('addresses.municipality', $municipalities_access );
					}
					$query->whereIn('degree', $accessed_degree);
					$query->where('address', 'LIKE',"%{$request->address}%");
					$query->where('semester', 'LIKE', "{$request->semester}%");
					$query->where('academic_year', 'LIKE', "{$request->academic_year}%");
					$query->join('addresses', 'addresses.address_id', '=', 'scholars.addressId');
					$query->join('schools', 'schools.school_id', '=', 'scholars.schoolId');
					$query->join('courses', 'courses.course_id', '=', 'scholars.courseId');
					$query->join('academicyear_semester_contracts', 'academicyear_semester_contracts.asc_id', '=', 'scholars.contract_id');
					$query->where(DB::raw('CONCAT(lastname," ",firstname, " ",middlename)'), 'LIKE', "{$request->searched_name}%");
					$query->where('age', 'LIKE', $request->age);
					$query->where('gender', 'LIKE', $request->gender);
					$query->where('scholar_status', 'LIKE',  $request->scholar_status);
					$query->where('contract_status', 'LIKE',  $request->contract_status);
					$query->where('course', 'LIKE', $request->course);
					$query->where('section', 'LIKE', "{$request->section}%");
					$query->where('student_id_number', 'LIKE', "{$request->student_id_number}%");
					$query->where('year_level', 'LIKE', $request->year_level);
					$query->where('IP', 'LIKE', $request->IP);
					$query->where('schoolId', 'LIKE', $request->schoolId);
					$query->where('degree', 'LIKE', $request->degree);
					$query->orderBy('lastname');
					
		return $query->get();

	}

}
