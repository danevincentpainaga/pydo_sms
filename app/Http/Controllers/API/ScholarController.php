<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use File;
use App\Models\scholar;
use App\Models\academicyear_semester_contract;
use DB;

class ScholarController extends validateUserCredentials
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
	        $s->contract_id = $request->contract_id;
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
		return $this->returnedScholars($request, $request->searched, "NEW", "Pre-Approved", 'scholar_id', 'DESC', ["Undergraduate"]);
	}

	public function getNewMastersDoctorateScholars(Request $request)
	{
		return $this->returnedScholars($request, $request->searched, "NEW", "Pre-Approved", 'scholar_id', 'DESC', ["Masters", "Doctorate"]);
	}

	public function getScholars(Request $request)
	{
		return $this->returnedScholars($request, $request->searched_name, $request->scholar_status, $request->contract_status, 'lastname', 'ASC');
	}

	private function returnedScholars($request, $searched_name, $scholar_status, $contract_status, $columnToBeOrdered, $orderby, $accessedDegree = [])
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
				->orderBy($columnToBeOrdered, $orderby)
				->paginate(50);

			}
			
			return response()->json(['message'=> 'UnAuthorized. No municipality access!'], 403);	
		};

		return response()->json(['message'=> 'UnAuthorized!'], 403);
	}
	
    public function uploadProfilePic(Request $request){

        if ($request->hasFile('file')) {

        	$file = $request->file('file');

        	$fileName = $this->createFilename($file);

        	$mime = str_replace('/', '-', $file->getMimeType());
        	$dateFolder = date("Y-m-W");

            $filePath = "upload/{$mime}/{$dateFolder}/";

            $finalPath = storage_path("app/public/".$filePath);

            $file->move($finalPath, $fileName);



            if ($fileName) {
            	$s = scholar::findOrFail($request->scholar_id);
            	$oldfile = $s->photo;
            	$s->photo = $filePath . $fileName;
            	$s->save();

            	File::delete(storage_path("app/public/".$oldfile));
            }


	        return $s->photo;
        }

        return "No file";

    }

    private function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace([" ", ".".$extension], "", $file->getClientOriginalName()); 
        $filename .= "_" . md5(time()) . "." . $extension;

        return $filename;
    }

}
