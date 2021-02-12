<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;


    protected $primaryKey = 'course_id';
    public $timestamps = false;

    protected $fillable = [
        'course_id', 'course'
    ];
    
}
