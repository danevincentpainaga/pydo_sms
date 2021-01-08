<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class province extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'province_id ';
    public $timestamps = false;

    protected $fillable = [
        'province_name', 'province_id'
    ];
}
