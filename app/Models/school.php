<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class school extends Model
{
    use HasFactory;


    protected $primaryKey = 'school_id';
    public $timestamps = false;

    protected $fillable = [
        'school_name', 's_province_id'
    ];

    public function province(){
    	return $this->hasOne(province::class, 'province_id', 's_province_id');
    }
}
