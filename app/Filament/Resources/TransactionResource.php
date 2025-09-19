<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use App\Filament\Resources\TransactionResource\Pages;

class TransactionResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Rekap Transaksi';
    protected static ?string $pluralLabel = 'Rekap Transaksi';
    protected static ?string $slug = 'rekap-transaksi';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')->label('Nama Pelanggan')->searchable(),
                Tables\Columns\TextColumn::make('booking_date')->label('Tanggal')->date(),
                Tables\Columns\TextColumn::make('booking_time')->label('Waktu'),
                Tables\Columns\TextColumn::make('total_price')->label('Total Harga')->money('IDR', true),
                Tables\Columns\TextColumn::make('payment_status')->label('Status Pembayaran')->badge()->color(fn ($state) => match ($state) {
                    'pending' => 'warning',
                    'success' => 'success',
                    'failed' => 'danger',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->since(),
            ])
            ->filters([
                //
            ])
            ->actions([]) // Tidak ada tombol edit/delete
            ->bulkActions([]); // Tidak ada bulk action
    }

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}

