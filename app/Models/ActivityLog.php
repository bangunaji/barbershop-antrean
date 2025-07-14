<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon; 

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'loggable_type',
        'loggable_id',
        'old_data',
        'new_data',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime', 
        'updated_at' => 'datetime', 
    ];

    
    public function getCreatedAtAttribute($value): Carbon
    {
        return Carbon::parse($value)->setTimezone(config('app.timezone'));
    }

    public function getUpdatedAtAttribute($value): Carbon
    {
        return Carbon::parse($value)->setTimezone(config('app.timezone'));
    }
    

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Helper to create a new log entry.
     */
    public static function createLog(
        string $action,
        Model $loggable,
        string $description,
        ?array $oldData = null,
        ?array $newData = null
    ): self {
        return self::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'action' => $action,
            'loggable_type' => get_class($loggable),
            'loggable_id' => $loggable->id,
            'old_data' => $oldData,
            'new_data' => $newData,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            
        ]);
    }
}