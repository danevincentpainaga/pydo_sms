<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    use HasFactory;


    protected $primaryKey = 'address_id';
    public $timestamps = false;

    protected $fillable = [
        'address_id', 'barangay_name', 'municipality',
    ];
    
}
