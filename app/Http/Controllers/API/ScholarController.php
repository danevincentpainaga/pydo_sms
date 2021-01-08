<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\school;
use App\Models\father_detail;
use App\Models\mother_detail;

class ScholarController extends Controller
{

	public function saveScholar(Request $request)
	{
		$fid;
		$mid;

		if (!$request->fatherId) {
			$fid = $this->addFather($request);
		}

		if (!$request->addMother) {
			$mid = $this->addMother($request);
		}

		return response()->json(['message'=>'Successfully Added'], 200);		
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

	public function saveScholarDetails(){
		
	}


}
