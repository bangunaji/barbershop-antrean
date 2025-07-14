<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB; 

class BookingsChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Booking (7 Hari Terakhir)'; 

    protected static ?int $sort = 3; 

    protected function getType(): string
    {
        return 'line'; 
    }

    protected function getData(): array
    {
        $data = $this->getBookingDataForLastDays(7);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Booking',
                    'data' => array_values($data),
                    'borderColor' => '#3b82f6', 
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getBookingDataForLastDays(int $days): array
    {
        $endDate = Carbon::today(config('app.timezone'));
        $startDate = $endDate->copy()->subDays($days - 1);

        $dates = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dates[$currentDate->translatedFormat('d M')] = 0;
            $currentDate->addDay();
        }

        $bookings = Booking::query()
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->whereIn('booking_status', ['active', 'completed', 'cancelled'])
            ->select(DB::raw('DATE(booking_date) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date, config('app.timezone'))->translatedFormat('d M');
            })
            ->map(fn ($item) => $item->count)
            ->toArray();

        return array_merge($dates, $bookings);
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}