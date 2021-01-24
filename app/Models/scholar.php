<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scholar extends Model
{
    use HasFactory;

    protected $primaryKey = 'scholar_id';

    protected $fillable = [
        'student_id_number', 'lastname', 'firstname', 'middlename','addressId ', 'date_of_birth', 'age', 'gender',
        'schoolId ', 'course_section', 'year_level', 'IP', 'fatherId ', 'photo', 'degree', 'scholar_status', 'contract_status', 'scholar_asc_id', 'last_renewed', 'sem_year_applied',
    ];

    public function school(){
    	return $this->HasOne(school::class, 'school_id', 'schoolId');
    }

    public function academicyear_semester_contract(){
    	return $this->HasOne(academicyear_semester_contract::class, 'asc_id', 'last_renewed');
    }

    public function father(){
        return $this->HasOne(father_detail::class, 'father_details_id', 'fatherId');
    }

    public function mother(){
        return $this->HasOne(mother_detail::class, 'mother_details_id', 'motherId');
    }

    public function address(){
        return $this->HasOne(address::class, 'address_id', 'addressId');
    }

}
