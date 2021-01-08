<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class father_detail extends Model
{
    use HasFactory;

    protected $primaryKey = 'father_details_id';
    public $timestamps = false;

    protected $fillable = [
        'f_lastname', 'f_firstname', 'f_middlename', 'f_occupation'
    ];

}
