<?php 

namespace App\Http\Controllers\API;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class ExportScholars implements FromQuery, WithTitle
{
    use Exportable;

    private $selected_columns = [
        'lastname',
        'firstname',
        'middlename',
        'date_of_birth',
    ];

    public function __construct($municipality)
    {
        $this->municipality = $municipality;
    }

    public function query()
    {
 		$query = DB::table('scholars');
					$query->whereIn('contract_status', ['Approved', 'Pre-Approved', 'Pending']);
					$query->join('addresses', 'addresses.address_id', '=', 'scholars.addressId');
					$query->join('schools', 'schools.school_id', '=', 'scholars.schoolId');
					$query->join('courses', 'courses.course_id', '=', 'scholars.courseId');
                    if ($this->municipality != "*") {
                        $query->where('addresses.municipality', $this->municipality);
                    }
					$query->join('academicyear_semester_contracts', 'academicyear_semester_contracts.asc_id', '=', 'scholars.last_renewed');
                    $query->select('lastname', 'firstname', 'middlename', 'date_of_birth', 
                        DB::raw(
                            "CONCAT(
                                JSON_UNQUOTE(JSON_EXTRACT(father_details, '$.firstname')), 
                                ' ',
                                JSON_UNQUOTE(JSON_EXTRACT(father_details, '$.middlename')),
                                ' ',
                                JSON_UNQUOTE(JSON_EXTRACT(father_details, '$.lastname'))
                            ) AS Father"
                        ),
                        DB::raw(
                            "CONCAT(
                                JSON_UNQUOTE(JSON_EXTRACT(mother_details, '$.firstname')), 
                                ' ',
                                JSON_UNQUOTE(JSON_EXTRACT(mother_details, '$.middlename')),
                                ' ',
                                JSON_UNQUOTE(JSON_EXTRACT(mother_details, '$.maiden_name'))
                            ) AS MOTHER"
                        )
                    );
					$query->orderBy('lastname');
					$query->get();

		return $query;
	}

    public function title(): string
    {
        return $this->municipality;
    }

    public function headings() : array
    {

    }
}