<?php

namespace App\Http\Controllers\API;

set_time_limit(0);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\API\ExportScholars;
use Spatie\SimpleExcel\SimpleExcelWriter;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use App\Models\scholar;
use DB;

class ExportScholarsController extends validateUserCredentials
{
	// private $excel;

	// public function __construct(Excel $excel)
	// {
	// 	$this->excel = $excel;
	// }

	// public function getScholarsToExport(Request $request)
	// {
		
	// 	// return (new MultipleSheets(['BELISON', 'SIBALOM']))->download('invoices.xlsx', \Maatwebsite\Excel\Excel::XLSX);
	// 	// $this->excel->store(new MultipleSheets(['BELISON', 'SIBALOM']), 'invoices.xlsx', 'public');
	// 	// return 'invoices.xlsx';
	// 	// $wb = [];

	// 	// $municipalities_access = $this->filteredMunicipality($request->municipality);
	// 	// $query = DB::table('scholars');
	// 	// 			if ($municipalities_access[0] != "*") {
	// 	// 				$query->whereIn('addresses.municipality', $municipalities_access );
	// 	// 			}
	// 	// 			$query->whereIn('degree', $this->getDegree());
	// 	// 			$query->whereIn('contract_status', ['Approved', 'Pre-Approved', 'Pending']);
	// 	// 			$query->join('addresses', 'addresses.address_id', '=', 'scholars.addressId');
	// 	// 			$query->join('schools', 'schools.school_id', '=', 'scholars.schoolId');
	// 	// 			$query->join('courses', 'courses.course_id', '=', 'scholars.courseId');
	// 	// 			$query->join('academicyear_semester_contracts', 'academicyear_semester_contracts.asc_id', '=', 'scholars.last_renewed');
	// 	// 			$query->select('scholars.*', 'addresses.*');
	// 	// 			$query->orderBy('lastname')->chunk(500, function ($scholars) use (&$wb){
	// 	// 				foreach ($scholars as $scholar) {
	// 	// 					$scholar->father_details = json_decode($scholar->father_details);
	// 	// 					$scholar->mother_details = json_decode($scholar->mother_details);
	// 	// 					$wb[$scholar->degree] = $scholar;
	// 	// 				}
	// 	// 			});


	// 	$result = [];
	// 	// $accessed_degree = json_decode(Auth::user()->degree_access);

	// 	$municipalities_access = $this->filteredMunicipality($request->municipality);
		
	// 	DB::disableQueryLog();
		
	// 	$query = DB::table('scholars');
	// 				if ($municipalities_access[0] != "*") {
	// 					$query->whereIn('addresses.municipality', $municipalities_access );
	// 				}
	// 				$query->whereIn('degree', $this->getDegree());
	// 				$query->whereIn('contract_status', ['Approved', 'Pre-Approved', 'Pending']);
	// 				// $query->where('address', 'LIKE',"%{$request->address}%");
	// 				// $query->where('semester', 'LIKE', "{$request->semester}%");
	// 				// $query->where('academic_year', 'LIKE', "{$request->academic_year}%");
	// 				$query->join('addresses', 'addresses.address_id', '=', 'scholars.addressId');
	// 				$query->join('schools', 'schools.school_id', '=', 'scholars.schoolId');
	// 				$query->join('courses', 'courses.course_id', '=', 'scholars.courseId');
	// 				$query->join('academicyear_semester_contracts', 'academicyear_semester_contracts.asc_id', '=', 'scholars.last_renewed');
	// 				// $query->where(DB::raw('CONCAT(lastname," ",firstname, " ",middlename)'), 'LIKE', "{$request->searched_name}%");
	// 				// $query->where('age', 'LIKE', $request->age);
	// 				// $query->where('gender', 'LIKE', $request->gender);
	// 				// $query->where('scholar_status', 'LIKE',  $request->scholar_status);
	// 				// $query->where('contract_status', 'LIKE',  $request->contract_status);
	// 				// $query->where('course', 'LIKE', $request->course);
	// 				// $query->where('section', 'LIKE', "{$request->section}%");
	// 				// $query->where('student_id_number', 'LIKE', "{$request->student_id_number}%");
	// 				// $query->where('year_level', 'LIKE', $request->year_level);
	// 				// $query->where('IP', 'LIKE', $request->IP);
	// 				// $query->where('schoolId', 'LIKE', $request->schoolId);
	// 				$query->orderBy('lastname')->chunk(3, function ($scholars) use (&$result){
	// 					$result[] = $scholars->toArray();
	// 					// foreach ($scholars as $scholar) {
	// 					// 	$scholar->father_details = json_decode($scholar->father_details);
	// 					// 	$scholar->mother_details = json_decode($scholar->mother_details);
	// 					// 	$result[] = $scholar;
	// 					// }
	// 				});
						
	// 	return $result;
	// }

	protected function getDegree(){

		$degree_access = Auth::user()->degree_access;

		if($degree_access[0] == "*") return ["Undergraduate", "Master", "Doctorate"];

		return $degree_access;
		
	}

	public function exportScholarsExcel(Request $request) {
		
		$rq = array('municipality'=> ['BELISON'], 'status'=> ['Approved', 'Pre-Approved', 'Pending']);

		date_default_timezone_set('Asia/Manila');
		$sheet_name = "BELISON-".date('Y-m-d');
		$file_name = 'scholars_list_'. round(microtime(true) * 1000).'.xlsx';

		$municipalities_access = $this->filteredMunicipality($rq['municipality']);

		$writer = SimpleExcelWriter::create(storage_path('app/templates/'. $file_name));
		$writer->nameCurrentSheet($sheet_name);

		$writer->setHeaderStyle(
			(new Style())
			->setFontSize(12)
			->setShouldWrapText()
			->setFontColor(Color::WHITE)
			->setBackgroundColor(Color::GREEN)
		);

		$sheet = $writer->getWriter()->getCurrentSheet();

		for ($i=1; $i < 21; $i++) { 
			$sheet->setColumnWidth(30, $i);
		}

		$query = DB::table('scholars');
		if ($municipalities_access[0] != "*") {
			$query->whereIn('addresses.municipality', $municipalities_access);
		}
		$query->whereIn('degree', ['Undergraduate']);
		$query->whereIn('contract_status', $rq['status']);
		$query->join('addresses', 'addresses.address_id', '=', 'scholars.addressId');
		$query->join('schools', 'schools.school_id', '=', 'scholars.schoolId');
		$query->join('courses', 'courses.course_id', '=', 'scholars.courseId');
		$query->join('academicyear_semester_contracts', 'academicyear_semester_contracts.asc_id', '=', 'scholars.last_renewed');
		$query->select('lastname', 'firstname', 'middlename', 'date_of_birth', 'age', 'gender',
				DB::RAW('CONCAT(brgy," ",municipality,", ANTIQUE") AS address'), 'school_name AS school', 'student_id_number',
				DB::RAW('"Undergraduate" AS degree'), 'course', 'section', 'year_level',
			DB::raw(
				"JSON_UNQUOTE(JSON_EXTRACT(father_details, '$.firstname')) AS father_firstname,
				 JSON_UNQUOTE(JSON_EXTRACT(father_details, '$.middlename')) AS father_middlename,
				 JSON_UNQUOTE(JSON_EXTRACT(father_details, '$.lastname')) AS father_lastname,
				 JSON_UNQUOTE(JSON_EXTRACT(mother_details, '$.firstname')) AS mother_firstname,
				 JSON_UNQUOTE(JSON_EXTRACT(mother_details, '$.middlename')) AS mother_middlename,
				 JSON_UNQUOTE(JSON_EXTRACT(mother_details, '$.maiden_name')) AS mother_maiden_name"
			)
		);

		$query->orderBy('lastname')->chunk(1, function ($scholars) use (&$writer) {
			$border = new Border(
				new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
				new BorderPart(Border::LEFT, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
				new BorderPart(Border::RIGHT, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
				new BorderPart(Border::TOP, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
			);
			
			$style = (new Style())
				->setFontSize(12)
				->setShouldWrapText()
				->setBorder($border);
				
			$writer->addRows(json_decode(json_encode($scholars), true), $style);
		});

		return response()->download(storage_path('app/templates/'. $file_name), $file_name, [
			'Content-Type' => 'application/vnd.ms-excel',
			'Content-Disposition' => 'attachment; filename=scholars_list.xlsx'
		])->deleteFileAfterSend(true);
	}

}