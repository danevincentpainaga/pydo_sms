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

		$fid = $this->getParentId($request, $request->fatherId, 'father');
		$mid = $this->getParentId($request, $request->motherId, 'mother');

		$data = $this->saveDetails($request, $fid, $mid);

		return response()->json(['result'=> $data], 200);		
	}

	public function addMother($request)
	{
        $m = new mother_detail();
        $m->m_firstname = $request->mother['m_firstname'];
        $m->m_lastname = $request->mother['m_lastname'];
        $m->m_middlename = $request->mother['m_middlename'];
        $m->save();
        return $m->mother_details_id;
	}

	public function addFather($request)
	{
        $f = new father_detail();
        $f->f_firstname = $request->father['f_firstname'];
        $f->f_lastname = $request->father['f_lastname'];
        $f->f_middlename = $request->father['f_middlename'];
        $f->save();

        return $f->father_details_id;
	}

	public function saveScholarDetails()
	{
		
	}

	private function getParentId($request, $parentId, $parent)
	{
		if (!$parentId) {
			if ($parent == 'father') {
				return $this->addFather($request);
			}
			else{
				return $this->addMother($request);
			}
		}

		return $parentId;
	}

	private function saveDetails($request, $fatherId, $motherId)
	{
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
        $s->fatherId = $fatherId;
        $s->motherId = $motherId;
        $s->degree = $request->degree;
        $s->scholar_status = 'NEW';
        $s->contract_status = 'Pre-Approved';
        $s->scholar_asc_id = $request->scholar_asc_id;
        $s->sem_year_applied = $request->scholar_asc_id;
        $s->save();

        return $s;	
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
				->with(['address', 'father', 'mother', 'school', 'academicyear_semester_contract'])
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

		if (!$degree || in_array($degree, $accessed_degree)) {
			return true;
		}
	}

	private function filterScholarDegree($accessedDegree){
		if ($accessedDegree) 

			return $accessed_degree = array_values(array_intersect(json_decode(Auth::user()->scholars_access), $accessedDegree));

		return json_decode(Auth::user()->scholars_access);
	}

}
