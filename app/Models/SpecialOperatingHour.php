<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialOperatingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'open_time',
        'close_time',
        'is_closed',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'open_time' => 'datetime',
        'close_time' => 'datetime',
        'is_closed' => 'boolean',
    ];
}