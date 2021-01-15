<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class semester extends Model
{
    use HasFactory;

    protected $primaryKey = 'semester_id';
    public $timestamps = false;

    protected $fillable = [
        'semester_id', 'semester'
    ];

    public function academicyearSemesterContract()
    {
    	return $this->belongsTo(academicyear_semester_contract::class, 'semesterId', 'semester_id');
    }

    // public function hasScholar()
    // {
    //     return $this->hasOneThrough(scholar::class, academicyear_semester_contract::class, 'semesterId', 'scholar_asc_id', 'semester_id', 'asc_id');
    // }
}
