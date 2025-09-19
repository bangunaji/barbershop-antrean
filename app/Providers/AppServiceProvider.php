<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; 

use App\Models\Booking;
use App\Observers\BookingObserver;
use App\Models\Service;
use App\Observers\ServiceObserver;
use App\Models\DefaultOperatingHour;
use App\Observers\DefaultOperatingHourObserver;
use App\Models\SpecialOperatingHour;
use App\Observers\SpecialOperatingHourObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Booking::observe(BookingObserver::class);
        Service::observe(ServiceObserver::class);
        DefaultOperatingHour::observe(DefaultOperatingHourObserver::class);

        
        config(['app.locale' => 'id']); 
        Carbon::setLocale('id'); 
        

        
        $now = Carbon::now(config('app.timezone'));
        $offsetInSeconds = $now->getOffset();
        
        $offsetHours = floor(abs($offsetInSeconds) / 3600);
        $offsetMinutes = floor((abs($offsetInSeconds) % 3600) / 60);
        $sign = ($offsetInSeconds < 0 ? '-' : '+');
        
        $offset = sprintf('%s%02d:%02d', $sign, $offsetHours, $offsetMinutes);
        
        
        DB::connection()->getPdo()->exec("SET time_zone='{$offset}'");
    }
}