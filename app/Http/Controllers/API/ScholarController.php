<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Requests\validateUpdateScholarsRequest;
use App\Http\Requests\validateScholarsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use File;
use App\Models\scholar;
use App\Models\academicyear_semester_contract;
use DB;

class ScholarController extends validateUserCredentials
{

	public function storeNewScholarDetails(validateScholarsRequest $request)
	{
		try {

			$scholarExist = scholar::where(['lastname' => $this->trimAndAcceptLettersSpacesOnly($request->lastname), 'firstname' => $this->trimAndAcceptLettersSpacesOnly($request->firstname), 'middlename' => $this->trimAndAcceptLettersSpacesOnly($request->middlename)])->count();

			if(!$scholarExist){

				$data = $this->saveDetails($request);
				
				return response()->json(['message'=> $data], 200);	

			}

			return response()->json(['message'=> 'Scholar already exist'], 403);

		} catch (Exception $e) {
			throw $e;
		}
	}


	public function updateScholarDetails(validateUpdateScholarsRequest $request)
	{
		try {

	        $scholar = scholar::findOrFail($request->scholar_id);
	        $scholar->student_id_number = $request->student_id_number;
	        $scholar->degree = $request->degree;
	        $scholar->lastname = $this->trimAndAcceptLettersSpacesOnly($request->lastname);
	        $scholar->firstname = $this->trimAndAcceptLettersSpacesOnly($request->firstname);
	        $scholar->middlename = $this->trimAndAcceptLettersSpacesOnly($request->middlename);
	        $scholar->addressId = $request->addressId;
	        $scholar->date_of_birth = $request->date_of_birth;
	        $scholar->age = $request->age;
	        $scholar->gender = $request->gender;
	        $scholar->schoolId  = $request->schoolId;
	        $scholar->courseId = $request->courseId;
	        $scholar->section = $request->section;
	        $scholar->year_level  = $request->year_level;
	        $scholar->IP = $request->IP;
	        $scholar->save();

			return $scholar->updated_at;

		} catch (Exception $e) {
			throw $e;
		}
	}

	private function saveDetails($request)
	{
		try {

			return scholar::create([
		        'student_id_number' => $request->student_id_number,
		        'lastname' => $this->trimAndAcceptLettersSpacesOnly($request->lastname),
		        'firstname' => $this->trimAndAcceptLettersSpacesOnly($request->firstname),
		        'middlename' => $this->trimAndAcceptLettersSpacesOnly($request->middlename),
		        'addressId' => $request->addressId,
		        'date_of_birth' => $request->date_of_birth,
		        'age' => $request->age,
		        'gender' => $request->gender,
		        'schoolId' => $request->schoolId,
		        'courseId' => $request->courseId,
		        'section' => $request->section,
		        'year_level' => $request->year_level,
		        'IP' => $request->IP,
		        'father_details' => $request->father_details,
		        'mother_details' => $request->mother_details,
		        'degree' => $request->degree,
		        'scholar_status' => 'NEW',
		        'contract_status' => 'Pre-Approved',
		        'contract_id' => $request->contract_id,
		        'last_renewed' => $request->asc_id,
		        'sem_year_applied' => $request->asc_id,
		        'userId' => Auth::id()

			]);

		} catch (Exception $e) {
			throw $e;
		}	
	}

	public function getNewUndergraduateScholars(Request $request)
	{
		return $this->returnedScholars($request, $request->searched, "NEW", "Pre-Approved", 'scholar_id', 'DESC', 10, ["Undergraduate"]);
	}

	public function getNewMastersDoctorateScholars(Request $request)
	{
		return $this->returnedScholars($request, $request->searched, "NEW", "Pre-Approved", 'scholar_id', 'DESC', 10, ["Masters", "Doctorate"]);
	}

	public function getScholars(Request $request)
	{
		return $this->returnedScholars($request, $request->searched_name, $request->scholar_status, $request->contract_status, 'lastname', 'ASC', 50);
	}

	private function returnedScholars($request, $searched_name, $scholar_status, $contract_status, $columnToBeOrdered, $orderby, $limit, $accessedDegree = [])
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
				->with(['address', 'school', 'course', 'academicyear_semester_contract:asc_id,semester,academic_year,undergraduate_amount,masteral_doctorate_amount'])		
				->whereIn('degree', $accessed_degree)
				->where(DB::raw('CONCAT(lastname," ",firstname, " ",middlename)'), 'LIKE', "{$searched_name}%")
				->where('scholar_status', 'LIKE',  $scholar_status)
				->where('contract_status', 'LIKE',  $contract_status)
				->where('degree', 'LIKE', $request->degree)
				->orderBy($columnToBeOrdered, $orderby)
				->paginate($limit);

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
            	$scholar = scholar::findOrFail($request->scholar_id);
            	$oldfile = $scholar->photo;
            	$scholar->photo = $filePath . $fileName;
            	$scholar->save();

            	File::delete(storage_path("app/public/".$oldfile));
            }


	        return $scholar->photo;
        }

        return "No file";

    }

    private function trimAndAcceptLettersSpacesOnly($value){
		return trim(preg_replace('/[^a-z\s]/i', '', $value));
    }

    private function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace([" ", ".".$extension], "", $file->getClientOriginalName()); 
        $filename .= "_" . md5(time()) . "." . $extension;

        return $filename;
    }

}
