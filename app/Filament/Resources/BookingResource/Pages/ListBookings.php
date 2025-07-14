<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns;
use Illuminate\Support\Carbon;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make('Antrean Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('booking_status', 'active')->whereDate('booking_date', today())->orderBy('sort_order', 'asc'))
                
                ->badge(Booking::query()->where('booking_status', 'active')->count()) 
                
                ->badgeColor('primary'),

            'completed' => Tab::make('Riwayat Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('booking_status', 'completed')->orderBy('updated_at', 'desc'))
                
                ->badge(Booking::query()->where('booking_status', 'completed')->count()) 
                
                ->badgeColor('success'),

            'cancelled' => Tab::make('Riwayat Batal')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('booking_status', 'cancelled')->orderBy('updated_at', 'desc'))
                
                ->badge(Booking::query()->where('booking_status', 'cancelled')->count()) 
                
                ->badgeColor('danger'),
            
            'all' => Tab::make('Semua Booking')
                ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('booking_date', 'desc')->orderBy('queue_number', 'asc'))
                
                ->badge(Booking::query()->count()), 
                
        ];
    }
}