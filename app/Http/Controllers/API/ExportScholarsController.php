<?php

namespace App\Http\Controllers\API;

set_time_limit(0);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\API\ExportScholars;
use Maatwebsite\Excel\Excel;
use App\Models\scholar;
use DB;

class ExportScholarsController extends validateUserCredentials
{
	private $excel;

	public function __construct(Excel $excel)
	{
		$this->excel = $excel;
	}

	public function getScholarsToExport(Request $request)
	{
		
		// return (new MultipleSheets(['BELISON', 'SIBALOM']))->download('invoices.xlsx', \Maatwebsite\Excel\Excel::XLSX);
		// $this->excel->store(new MultipleSheets(['BELISON', 'SIBALOM']), 'invoices.xlsx', 'public');
		// return 'invoices.xlsx';
		// $wb = [];

		// $municipalities_access = $this->filteredMunicipality($request->municipality);
		// $query = DB::table('scholars');
		// 			if ($municipalities_access[0] != "*") {
		// 				$query->whereIn('addresses.municipality', $municipalities_access );
		// 			}
		// 			$query->whereIn('degree', $this->getDegree());
		// 			$query->whereIn('contract_status', ['Approved', 'Pre-Approved', 'Pending']);
		// 			$query->join('addresses', 'addresses.address_id', '=', 'scholars.addressId');
		// 			$query->join('schools', 'schools.school_id', '=', 'scholars.schoolId');
		// 			$query->join('courses', 'courses.course_id', '=', 'scholars.courseId');
		// 			$query->join('academicyear_semester_contracts', 'academicyear_semester_contracts.asc_id', '=', 'scholars.last_renewed');
		// 			$query->select('scholars.*', 'addresses.*');
		// 			$query->orderBy('lastname')->chunk(500, function ($scholars) use (&$wb){
		// 				foreach ($scholars as $scholar) {
		// 					$scholar->father_details = json_decode($scholar->father_details);
		// 					$scholar->mother_details = json_decode($scholar->mother_details);
		// 					$wb[$scholar->degree] = $scholar;
		// 				}
		// 			});


		$result = [];
		// $accessed_degree = json_decode(Auth::user()->degree_access);

		$municipalities_access = $this->filteredMunicipality($request->municipality);
		
		DB::disableQueryLog();
		
		$query = DB::table('scholars');
					if ($municipalities_access[0] != "*") {
						$query->whereIn('addresses.municipality', $municipalities_access );
					}
					$query->whereIn('degree', $this->getDegree());
					$query->whereIn('contract_status', ['Approved', 'Pre-Approved', 'Pending']);
					// $query->where('address', 'LIKE',"%{$request->address}%");
					// $query->where('semester', 'LIKE', "{$request->semester}%");
					// $query->where('academic_year', 'LIKE', "{$request->academic_year}%");
					$query->join('addresses', 'addresses.address_id', '=', 'scholars.addressId');
					$query->join('schools', 'schools.school_id', '=', 'scholars.schoolId');
					$query->join('courses', 'courses.course_id', '=', 'scholars.courseId');
					$query->join('academicyear_semester_contracts', 'academicyear_semester_contracts.asc_id', '=', 'scholars.last_renewed');
					// $query->where(DB::raw('CONCAT(lastname," ",firstname, " ",middlename)'), 'LIKE', "{$request->searched_name}%");
					// $query->where('age', 'LIKE', $request->age);
					// $query->where('gender', 'LIKE', $request->gender);
					// $query->where('scholar_status', 'LIKE',  $request->scholar_status);
					// $query->where('contract_status', 'LIKE',  $request->contract_status);
					// $query->where('course', 'LIKE', $request->course);
					// $query->where('section', 'LIKE', "{$request->section}%");
					// $query->where('student_id_number', 'LIKE', "{$request->student_id_number}%");
					// $query->where('year_level', 'LIKE', $request->year_level);
					// $query->where('IP', 'LIKE', $request->IP);
					// $query->where('schoolId', 'LIKE', $request->schoolId);
					$query->orderBy('lastname')->chunk(3, function ($scholars) use (&$result){
						$result[] = $scholars->toArray();
						// foreach ($scholars as $scholar) {
						// 	$scholar->father_details = json_decode($scholar->father_details);
						// 	$scholar->mother_details = json_decode($scholar->mother_details);
						// 	$result[] = $scholar;
						// }
					});
						
		return $result;
	}

	protected function getDegree(){

		$degree_access = json_decode(Auth::user()->degree_access);

		if($degree_access[0] == "*") return ["Undergraduate", "Master", "Doctorate"];

		return $degree_access;
		
	}

	public function export() 
	{
	    return Excel::download(new scholarsExport, 'invoices.xlsx');
	}

}
