<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class governor extends Model
{
    use HasFactory;
    protected $primaryKey = 'governor_id';
    public $timestamps = false;

    protected $casts = [
        'governor' => 'array',
    ];

    protected $fillable = [
        'governor_id', 'selected', 'governor'
    ];
}
