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
        'schoolId ', 'IP', 'fatherId ', 'photo', 'degree', 'scholar_status', 'scholar_asc_id'
    ];

    public function school(){
    	return $this->HasOne(school::class, 'school_id', 'schoolId');
    }
}
