<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number; 

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        
        $today = Carbon::today(config('app.timezone'));

        $activeBookingsToday = Booking::whereDate('booking_date', $today)
                                    ->where('booking_status', 'active')
                                    ->count();

        $completedBookingsToday = Booking::whereDate('booking_date', $today)
                                        ->where('booking_status', 'completed')
                                        ->count();

        $totalRevenueToday = Booking::whereDate('booking_date', $today)
                                    ->where('booking_status', 'completed')
                                    ->sum('total_price');

        $allTimeBookings = Booking::count();
        $allTimeRevenue = Booking::where('booking_status', 'completed')->sum('total_price');

        
        
        $nextInQueueCount = Booking::whereDate('booking_date', $today)
                                ->where('booking_status', 'active')
                                ->orderBy('sort_order', 'asc')
                                ->count();


        return [
            Stat::make('Booking Aktif Hari Ini', $activeBookingsToday)
                ->description('Jumlah booking yang belum selesai hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'), 

            Stat::make('Booking Selesai Hari Ini', $completedBookingsToday)
                ->description('Jumlah booking yang telah selesai hari ini')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'), 

            Stat::make('Pendapatan Hari Ini', Number::currency($totalRevenueToday, 'IDR'))
                ->description('Total pendapatan dari booking yang selesai hari ini')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('warning'), 

            Stat::make('Total Seluruh Booking', $allTimeBookings)
                ->description('Jumlah booking sepanjang waktu')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'), 

            Stat::make('Total Pendapatan (Keseluruhan)', Number::currency($allTimeRevenue, 'IDR'))
                ->description('Pendapatan dari seluruh booking yang selesai')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'), 

            Stat::make('Antrean Berikutnya', $nextInQueueCount > 0 ? 'Ada ' . $nextInQueueCount . ' Orang' : 'Kosong')
                ->description('Jumlah orang dalam antrean aktif hari ini')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('secondary'), 
        ];
    }
}