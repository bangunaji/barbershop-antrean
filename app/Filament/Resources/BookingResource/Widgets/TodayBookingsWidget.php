<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookingResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TodayBookingsWidget extends BaseWidget
{
    protected static ?string $heading = 'Antrean Booking Hari Ini';
    
    protected int | string | array $columnSpan = 'full';

    public function getTableQuery(): Builder
    {
        
        return BookingResource::getModel()::query()
            ->whereDate('booking_date', today())
            ->orderBy('booking_time');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('queue_number')
                ->label('No. Antrean'),
            Tables\Columns\TextColumn::make('customer_name') 
                ->label('Pelanggan')
                ->searchable(),
            
            Tables\Columns\TextColumn::make('booking_time')
                ->time('H:i')
                ->label('Jam Booking'),
            Tables\Columns\TextColumn::make('services.name')
                ->label('Layanan')
                ->badge(),
            Tables\Columns\BadgeColumn::make('arrival_status')
                ->label('Kehadiran')
                ->colors([
                    'primary' => 'waiting',
                    'success' => 'arrived',
                    'warning' => 'late',
                ])->formatStateUsing(fn ($state) => match($state) {
                    'waiting' => 'Menunggu',
                    'arrived' => 'Datang',
                    'late' => 'Terlambat',
                    default => ucfirst($state),
                }),
        ];
    }
}
