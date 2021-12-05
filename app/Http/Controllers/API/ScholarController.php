<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Requests\validateUpdateScholarsRequest;
use App\Http\Requests\validateScholarsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use File;
use App\Models\scholar;
use App\Models\academicyear_semester_contract;
use DB;

class ScholarController extends validateUserCredentials
{

	public function storeNewScholarDetails(validateScholarsRequest $request)
	{
		try {

			$scholarExist = scholar::where([
					'lastname' => $this->trimAndAcceptLettersSpacesOnly($request->lastname),
					'firstname' => $this->trimAndAcceptLettersSpacesOnly($request->firstname),
					'middlename' => $this->trimAndAcceptLettersSpacesOnly($request->middlename),
					'suffix' => $request->suffix
			])->count();

			if(!$scholarExist){

				$data = $this->saveDetails($request);
				
				return response()->json(['data'=> $data], 200);	

			}

			return response()->json(['exist'=> true, 'message'=> $scholarExist .' data matched'], 422);

		} catch (Exception $e) {
			throw $e;
		}
	}

	public function storeNewScholarBySupervisorsApproval(validateScholarsRequest $request)
	{
		try {

			$scholarExist = scholar::where([
					'lastname' => $this->trimAndAcceptLettersSpacesOnly($request->lastname),
					'firstname' => $this->trimAndAcceptLettersSpacesOnly($request->firstname),
					'middlename' => $this->trimAndAcceptLettersSpacesOnly($request->middlename),
					'suffix' => $request->suffix
			])
			->whereJsonContains('mother_details->maiden_name', $request->mother_details['maiden_name'])
			->whereJsonContains('mother_details->middlename', $request->mother_details['middlename'])
			->whereJsonContains('mother_details->firstname', $request->mother_details['firstname'])
			->orWhere('student_id_number', $request->student_id_number)
			->count();

			if(!$scholarExist){

				$data = $this->saveDetails($request);
				
				return response()->json(['data'=> $data], 200);

			}

			return response()->json(['exist'=> true, 'message'=> 'Scholar already exist'], 422);

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
	        $scholar->suffix = $request->suffix;
	        $scholar->addressId = $request->addressId;
	        $scholar->date_of_birth = $request->date_of_birth;
	        $scholar->age = $request->age;
	        $scholar->gender = $request->gender;
	        $scholar->schoolId  = $request->schoolId;
	        $scholar->courseId = $request->courseId;
	        $scholar->section = $request->section;
	        $scholar->year_level  = $request->year_level;
	        $scholar->civil_status = $request->civil_status;
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
		        'suffix' => $request->suffix,
		        'addressId' => $request->addressId,
		        'date_of_birth' => $request->date_of_birth,
		        'age' => $request->age,
		        'gender' => $request->gender,
		        'civil_status' => $request->civil_status,
		        'schoolId' => $request->schoolId,
		        'courseId' => $request->courseId,
		        'section' => $request->section,
		        'year_level' => $request->year_level,
		        'IP' => $request->IP,
		        'father_details' => $this->makeNullEmptyString($request->father_details),
		        'mother_details' => $this->makeNullEmptyString($request->mother_details),
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

	private function makeNullEmptyString($arr){
    	foreach ($arr as $key => $value) {
    		if(!$value){
    			$arr[$key] = "";
    		}
    	}
    	return $arr;
	}

	public function getNewUndergraduateScholars(Request $request)
	{
		return $this->returnedScholars($request, $request->searched, "NEW", ["Pre-Approved"], 'scholar_id', 'DESC', 2, ["Undergraduate"]);
	}

	public function getNewMastersDoctorateScholars(Request $request)
	{
		return $this->returnedScholars($request, $request->searched, "NEW", ["Pre-Approved"], 'scholar_id', 'DESC', 10, ["Masters", "Doctorate"]);
	}

	public function getScholars(Request $request)
	{
		$contract_status = $request->contract_status ? [$request->contract_status] : "";
		return $this->returnedScholars($request, $request->searched_name, $request->scholar_status, $contract_status, 'lastname', 'ASC', 3);
	}

    public function getNotRenewedScholar(Request $request){
    	return $this->returnedScholars($request, $request->searched_name, "OLD" , ["Pending"], 'lastname', 'ASC', 1, [$request->degree]);
    }

	private function returnedScholars($request, $searched_name, $scholar_status, $contract_status, $columnToBeOrdered, $orderby, $limit, $accessedDegree = [])
	{
		$accessed_degree = $this->filterScholarDegree($accessedDegree);

		if ($this->validateDegree($request->degree, $accessed_degree)) {

			$municipalities_access = $this->filteredMunicipality($request->municipality);

			if ($municipalities_access) {

				$query = scholar::query();

				$query->whereHas('address', function($query) use ($municipalities_access){

					if ($municipalities_access[0] != "*") {
						$query->whereIn('municipality', $municipalities_access );
					}

				});
				$query->with(['address', 'school', 'course', 'academicyear_semester_contract:asc_id,semester,academic_year,undergraduate_amount,masteral_doctorate_amount']);
				$query->whereIn('degree', $accessed_degree);
				$query->where(DB::raw('CONCAT(lastname," ",firstname, " ",middlename)'), 'LIKE', "{$searched_name}%");
				$query->where('scholar_status', 'LIKE',  $scholar_status);
				if($contract_status){
					$query->whereIn('contract_status', $contract_status);
				}
				$query->where('degree', 'LIKE', $request->degree);
				$query->orderBy($columnToBeOrdered, $orderby);
				return $query->paginate($limit);
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

        return response()->json(["message" => "No FIle"], 500);

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

    public function renewScholar(Request $request){

		$scholar = scholar::findOrFail($request->scholar_id);
		
		if($scholar->contract_status == 'Pre-Approved' || $scholar->contract_status == 'Approved'){
			return response()->json(['message'=> 'Scholar is already renewed'], 422);
		}

		$scholar->contract_status = 'Pre-Approved';
        $scholar->contract_id = $request->contract_id;
        $scholar->last_renewed = $request->last_renewed;
		$scholar->save();

		return response()->json(['message'=> 'Scholar renewed'], 200);

    }

}
