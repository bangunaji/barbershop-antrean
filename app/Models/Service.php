<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_minutes',
    ];

    /**
     * Satu layanan bisa dimiliki oleh banyak booking.
     */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class);
    }
}
