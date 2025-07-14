<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB; 

class BookingStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Status Booking Hari Ini';

    protected static ?int $sort = 2; 

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $today = Carbon::today(config('app.timezone'));
        $activeCount = Booking::where('booking_status', 'active')->whereDate('booking_date', $today)->count();
        $completedCount = Booking::where('booking_status', 'completed')->whereDate('booking_date', $today)->count();
        $cancelledCount = Booking::where('booking_status', 'cancelled')->whereDate('booking_date', $today)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Booking',
                    'data' => [$activeCount, $completedCount, $cancelledCount],
                    'backgroundColor' => ['#f59e0b', '#10b981', '#ef4444'], 
                ],
            ],
            'labels' => ['Aktif', 'Selesai', 'Dibatalkan'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'right', 
                ],
            ],
        ];
    }
}