<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\scholar;
use App\Models\father_detail;
use App\Models\mother_detail;
use App\Models\academicyear_semester_contract;
use DB;

class ScholarController extends Controller
{

	public function saveNewScholarDetails(Request $request)
	{
		$data = $this->saveDetails($request);

		return response()->json(['result'=> $data], 200);	
	}


	public function updateScholarDetails(Request $request)
	{
		try {

		    $request->validate([
		    	'scholar_id' => 'required',
		        'student_id_number' => 'required',
		        'lastname' => 'required',
		        'firstname' => 'required',
		        'middlename' => 'required',
		        'addressId' => 'required',
		        'date_of_birth' => 'required|min:10|max:10',
		        'age' => 'required|min:2|max:2',
		        'gender' => 'required|min:4|max:6',
		        'schoolId' => 'required',
		        'course_section' => 'required',
		        'year_level' => 'required',
		        'IP' => 'required',
		    ]);

	        $s = scholar::find($request->scholar_id);
	        $s->student_id_number = $request->student_id_number;
	        $s->lastname = $request->lastname;
	        $s->firstname = $request->firstname;
	        $s->middlename = $request->middlename;
	        $s->addressId = $request->addressId;
	        $s->date_of_birth = $request->date_of_birth;
	        $s->age = $request->age;
	        $s->gender = $request->gender;
	        $s->schoolId  = $request->schoolId;
	        $s->course_section  = $request->course_section;
	        $s->year_level  = $request->year_level;
	        $s->IP = $request->IP;
	        $s->save();

			return $s->updated_at;

		} catch (Exception $e) {
			throw $e;
		}
	}

	private function saveDetails($request)
	{
		try {

		    $request->validate([
		        'student_id_number' => 'required',
		        'lastname' => 'required',
		        'firstname' => 'required',
		        'middlename' => 'required',
		        'addressId' => 'required',
		        'date_of_birth' => 'required|min:10|max:10',
		        'age' => 'required|min:2|max:2',
		        'gender' => 'required|min:4|max:6',
		        'schoolId' => 'required',
		        'course_section' => 'required',
		        'year_level' => 'required',
		        'IP' => 'required',
		        'father_details' => 'required',
		        'mother_details' => 'required',
		        'degree' => 'required',
		        'asc_id' => 'required',
		    ]);

	        $s = new scholar();
	        $s->student_id_number = $request->student_id_number;
	        $s->lastname = $request->lastname;
	        $s->firstname = $request->firstname;
	        $s->middlename = $request->middlename;
	        $s->addressId = $request->addressId;
	        $s->date_of_birth = $request->date_of_birth;
	        $s->age = $request->age;
	        $s->gender = $request->gender;
	        $s->schoolId  = $request->schoolId;
	        $s->course_section  = $request->course_section;
	        $s->year_level  = $request->year_level;
	        $s->IP = $request->IP;
	        $s->father_details = $request->father_details;
	        $s->mother_details = $request->mother_details;
	        $s->degree = $request->degree;
	        $s->scholar_status = 'NEW';
	        $s->contract_status = 'Pre-Approved';
	        $s->scholar_asc_id = $request->asc_id;
	        $s->last_renewed = $request->asc_id;
	        $s->sem_year_applied = $request->asc_id;
	        $s->save();

	        return $s;

		} catch (Exception $e) {
			throw $e;
		}	
	}

	public function getNewUndergraduateScholars(Request $request)
	{
		return $this->returnedScholars($request, $request->searched, "NEW", "Pre-Approved", ["Undergraduate"]);
	}

	public function getNewMastersDoctorateScholars(Request $request)
	{
		return $this->returnedScholars($request, $request->searched, "NEW", "Pre-Approved", ["Masters", "Doctorate"]);
	}

	public function getScholars(Request $request)
	{
		return $this->returnedScholars($request, $request->searched_name, $request->scholar_status, $request->contract_status);
	}

	private function returnedScholars($request, $searched_name, $scholar_status, $contract_status, $accessedDegree = [])
	{
		$accessed_degree = $this->filterScholarDegree($accessedDegree);

		if ($this->validateDegree($request->degree, $accessed_degree)) {

			$municipalities_access = $this->filteredMunicipality($request->municipality);

			if ($municipalities_access) {

				return scholar::whereHas('address', function($query) use ($municipalities_access){

					if ($municipalities_access[0] != "*") {
						$query->whereIn('municipality', $municipalities_access );
					}

				})
				->with(['address', 'school', 'academicyear_semester_contract'])
				->whereIn('degree', $accessed_degree)
				->where(DB::raw('CONCAT(lastname," ",firstname, " ",middlename)'), 'LIKE', "{$searched_name}%")
				->where('scholar_status', 'LIKE',  $scholar_status)
				->where('contract_status', 'LIKE',  $contract_status)
				->where('degree', 'LIKE', $request->degree)
				->orderBy('lastname')
				->get();

			}
			
			return response()->json(['message'=> 'UnAuthorized. No municipality access!'], 403);	
		};

		return response()->json(['message'=> 'UnAuthorized!'], 403);
	}

	private function filteredMunicipality($municipality){

		if (!$municipality || $municipality == "Municipality") {
			return json_decode(Auth::user()->municipal_access);
		}

		return [$municipality];
	}

	private function validateDegree($degree, $accessed_degree ){

		if (!$degree || in_array($degree, $accessed_degree)) return true;
		
	}

	private function filterScholarDegree($accessedDegree){
		if ($accessedDegree) 

			return $accessed_degree = array_values(array_intersect(json_decode(Auth::user()->degree_access), $accessedDegree));

		return json_decode(Auth::user()->degree_access);
	}

}
