<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\scholar;
use App\Models\father_detail;
use App\Models\mother_detail;
use App\Models\academicyear_semester_contract;
use DB;

class ScholarController extends Controller
{

	public function saveScholar(Request $request)
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

	private function saveDetails($request, $fatherId, $motherId){
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
        $s->scholar_status = $request->scholar_status;
        $s->contract_status = $request->contract_status;
        $s->scholar_asc_id = $request->scholar_asc_id;
        $s->save();

        return $s;	
	}

	public function getNewScholars(Request $request)
	{
		return scholar::with(['father', 'mother', 'school', 'academicyear_semester_contract' => function($q){
			$q->join('semesters', 'semesters.semester_id', '=', 'semesterId');
		}])
		->where('scholar_status', 'NEW')
		->where(DB::raw('CONCAT(lastname," ",firstname, " ",middlename)'), 'LIKE', "{$request->searched}%")
		->orderBy('scholar_id', 'DESC')
		->get();
	}

	public function getScholars(Request $request)
	{
		if ($request->hasAny(['scholar_status', 'searched_name', 'contract_status'])) 
		{
			return $this->returnedScholars($request, $request->searched_name);
		}

		return $this->returnedScholars($request, "");
	}

	private function returnedScholars($request, $searched_name)
	{
		return scholar::with(['father', 'mother', 'school', 'academicyear_semester_contract' => function($q){

			$q->join('semesters', 'semesters.semester_id', '=', 'semesterId');

		}, 'address' => function($q){

			$q->join('municipalities', 'municipalities.municipality_id', '=', 'municipalityId')
			  ->join('provinces', 'provinces.province_id', '=', 'provinceId');

		}, ])
		->where(DB::raw('CONCAT(lastname," ",firstname, " ",middlename)'), 'LIKE', "{$searched_name}%")
		->where('scholar_status', 'LIKE',  $request->scholar_status)
		->where('contract_status', 'LIKE',  $request->contract_status)
		->where('degree', $request->degree)
		->get();
	}
}
