<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Support\Carbon;

class Booking extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'booking_type',
        'booking_date',
        'booking_time',
        'notes',
        'total_price',
        'queue_number',
        'sort_order',
        'shifted_by_admin',
        'arrival_status',
        'booking_status',
        'total_duration_minutes',
        'estimated_turn_time',   
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime',
        'total_duration_minutes' => 'integer',
        'estimated_turn_time' => 'datetime',
        'shifted_by_admin' => 'boolean', 
    ];

    public function getTotalDurationMinutesAttribute(): int
    {
        return $this->services->sum('duration_minutes');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $bookingDateFormatted = $booking->booking_date->format('dm');
            $prefix = 'BB-' . $bookingDateFormatted . '-';

            $lastBookingWithSequence = self::whereDate('booking_date', $booking->booking_date)
                                           ->whereNotNull('queue_number')
                                           ->where('queue_number', 'LIKE', $prefix . '%')
                                           ->orderByRaw("CAST(SUBSTRING_INDEX(queue_number, '-', -1) AS UNSIGNED) DESC")
                                           ->first();

            $nextSequence = 1;
            if ($lastBookingWithSequence && $lastBookingWithSequence->queue_number) {
                $lastSequence = (int) substr($lastBookingWithSequence->queue_number, -2);
                $nextSequence = $lastSequence + 1;
            }
            $booking->queue_number = $prefix . str_pad($nextSequence, 2, '0', STR_PAD_LEFT);
            
            if (empty($booking->customer_name)) {
                if ($booking->booking_type === 'online' && !empty($booking->user_id)) {
                    $user = User::find($booking->user_id);
                    $booking->customer_name = $user ? $user->name : 'Registered User (Name Missing)';
                } elseif ($booking->booking_type === 'walk-in') {
                    $booking->customer_name = 'Walk-in Customer (Name Missing)';
                } else {
                    $booking->customer_name = 'Unknown Customer';
                }
            }

            if (is_null($booking->total_duration_minutes) && !empty($booking->services)) {
                $booking->total_duration_minutes = $booking->getTotalDurationMinutesAttribute();
            } elseif (is_null($booking->total_duration_minutes)) {
                $booking->total_duration_minutes = 0;
            }

            if ($booking->booking_type === 'walk-in' && is_null($booking->booking_time)) {
                $booking->booking_time = null;
            }
        });

        static::created(function($booking) {
            static::recalculateQueueNumbersAndSortOrder($booking->booking_date->toDateString());
        });

        static::updated(function($booking) {
            static::recalculateQueueNumbersAndSortOrder($booking->booking_date->toDateString());
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function buildSortQuery()
    {
        return static::query()
            ->whereDate('booking_date', $this->booking_date)
            ->whereIn('booking_status', ['active', 'waiting', 'arrived', 'late']);
    }

    public static function recalculateQueueNumbersAndSortOrder(string $dateString): void
    {
        $bookingsForRecalculation = Booking::whereDate('booking_date', $dateString)
                                ->whereIn('booking_status', ['active']) 
                                ->orderBy('sort_order', 'asc') 
                                ->get();

        $shopOpenTime = \Illuminate\Support\Carbon::parse(self::getDailyShopOpenTime(\Illuminate\Support\Carbon::parse($dateString)));
        $currentServiceTime = $shopOpenTime->copy(); 
        $currentSortOrder = 1;

        foreach ($bookingsForRecalculation as $booking) {
            $booking->sort_order = $currentSortOrder++; 
            $booking->estimated_turn_time = $currentServiceTime->copy();
            $currentServiceTime->addMinutes($booking->total_duration_minutes);
            $booking->saveQuietly(); 
        }

        $nonActiveBookings = Booking::whereDate('booking_date', $dateString)
                                    ->whereIn('booking_status', ['completed', 'cancelled'])
                                    ->get();

        foreach ($nonActiveBookings as $booking) {
            if (!is_null($booking->sort_order) || !is_null($booking->estimated_turn_time)) {
                $booking->sort_order = null; 
                $booking->estimated_turn_time = null; 
                $booking->saveQuietly();
            }
        }
    }

    public static function getDailyShopOpenTime(\Illuminate\Support\Carbon $date): string
    {
       

        $defaultOpenTime = \App\Models\Setting::where('key', 'default_open_time')->first();
        $isShopClosedDefault = \App\Models\Setting::where('key', 'is_barbershop_closed_default')->first();

        if ($isShopClosedDefault && $isShopClosedDefault->value === '1') {
            return '00:00:00';
        }
        
        return $defaultOpenTime ? \Illuminate\Support\Carbon::parse($defaultOpenTime->value)->format('H:i:s') : '09:00:00';
    }
}
