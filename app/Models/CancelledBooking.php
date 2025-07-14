<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class CancelledBooking extends Model
{
    protected $guarded = [];
    protected $casts = ['services_snapshot' => 'array'];

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}