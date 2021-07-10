<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scholar extends Model
{
    use HasFactory;

    protected $primaryKey = 'scholar_id';

    protected $fillable = [
        'student_id_number', 'lastname', 'firstname', 'middlename','addressId', 'date_of_birth', 'age', 'gender',
        'schoolId', 'courseId', 'section', 'year_level', 'IP', 'father_details', 'mother_details', 'photo', 'degree', 'scholar_status', 'contract_status', 'contract_id', 'last_renewed', 'sem_year_applied', 'userId',
    ];

    protected $casts = [
        'father_details' => 'array',
        'mother_details' => 'array',
    ];

    public function course(){
        return $this->HasOne(course::class, 'course_id', 'courseId');
    }

    public function school(){
    	return $this->HasOne(school::class, 'school_id', 'schoolId');
    }

    public function academicyear_semester_contract(){
    	return $this->HasOne(academicyear_semester_contract::class, 'asc_id', 'last_renewed');
    }

    public function address(){
        return $this->HasOne(address::class, 'address_id', 'addressId');
    }
    
    public function activated_contract(){
        return $this->belongsTo(activated_contract::class, 'contract_id', 'activated_contract_id');
    }
    
}
