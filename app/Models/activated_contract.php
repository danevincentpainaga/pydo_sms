<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activated_contract extends Model
{
    use HasFactory;
    protected $table = 'activated_contract';

    protected $primaryKey = 'activated_contract_id';

    protected $fillable = ['ascId', 'contract_state'];

    public function academicYearSemester(){
    	return $this->belongsTo(academicyear_semester_contract::class, 'ascId', 'asc_id');
    }

}
