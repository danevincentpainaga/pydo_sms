<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class municipality extends Model
{
    use HasFactory;

    protected $primaryKey = 'municipality_id';
    public $timestamps = false;
    
    protected $fillable = [
        'municipality', 'provinceId'
    ];
    
    public function address(){
    	return $this->belongsTo(address::class, 'municipalityId', 'municipality_id');
    }

}
