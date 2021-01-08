<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mother_detail extends Model
{
    use HasFactory;

    protected $primaryKey = 'mother_details_id';
    public $timestamps = false;

    protected $fillable = [
        'm_lastname', 'm_firstname', 'm_middlename', 'm_occupation'
    ];

}
