<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefundRequestResource\Pages;
use App\Models\RefundRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class RefundRequestResource extends Resource
{
    protected static ?string $model = RefundRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationLabel = 'Refund Requests';
   

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('reason')
                    ->label('Alasan Refund')
                    ->disabled(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.queue_number')
        ->label('Kode Antrean'),

    TextColumn::make('booking.user.name')
        ->label('Nama Pelanggan'),

    TextColumn::make('reason')->label('Alasan')->limit(50),

    BadgeColumn::make('status')
        ->label('Status')
        ->colors([
            'secondary' => 'pending',
            'success' => 'approved',
            'danger' => 'rejected',
        ]),

    TextColumn::make('created_at')->label('Diajukan')->dateTime('d M Y H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->label('Tinjau'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRefundRequests::route('/'),
            'create' => Pages\CreateRefundRequest::route('/create'),
            'edit' => Pages\EditRefundRequest::route('/{record}/edit'),
        ];
    }
}
