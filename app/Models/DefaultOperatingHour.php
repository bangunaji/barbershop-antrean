<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultOperatingHour extends Model
{
    use HasFactory;
    protected $fillable = [
        'day_of_week',
        'open_time',
        'close_time',
        'is_closed',
    ];
    protected $casts = [
        'is_closed' => 'boolean',
        'open_time' => 'datetime',
        'close_time' => 'datetime',
    ];
}