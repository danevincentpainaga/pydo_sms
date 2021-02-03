<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class academicyear_semester_contract extends Model
{
    use HasFactory;

    protected $primaryKey = 'asc_id';

    protected $fillable = [
        'asc_id', 'semester', 'state', 'amount', 'academic_year'
    ];

    public function scholar(){
    	return $this->hasOne(scholar::class, 'scholar_asc_id', 'asc_id');
    }

}
