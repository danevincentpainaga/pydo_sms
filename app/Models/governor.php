<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class governor extends Model
{
    use HasFactory;
    protected $primaryKey = 'governor_id';
    protected $fillable = [
        'governor_id', 'selected', 'firstname', 'mi', 'lastname'
    ];
}
