<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Log Aktivitas';
    protected static ?string $pluralModelLabel = 'Log Aktivitas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Log tidak seharusnya diedit melalui form, hanya dilihat
                Forms\Components\TextInput::make('action')->disabled(),
                Forms\Components\TextInput::make('description')->columnSpanFull()->disabled(),
                Forms\Components\KeyValue::make('old_data')->disabled(),
                Forms\Components\KeyValue::make('new_data')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Aksi')
                    ->dateTime(format: 'd M Y, H:i:s') // Tentukan format eksplisit
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Admin')
                    ->default('System') // Jika user_id null
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi Aktivitas')
                    ->wrap() // Agar teks panjang bisa wrap
                    ->searchable(),
                Tables\Columns\TextColumn::make('action')
                    ->label('Jenis Aksi')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('loggable_type')
                    ->label('Model Terkait')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('loggable_id')
                    ->label('ID Terkait')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'created_booking' => 'Buat Booking',
                        'updated_booking' => 'Update Booking',
                        'created_service' => 'Buat Layanan',
                        'updated_service' => 'Update Layanan',
                        'deleted_service' => 'Hapus Layanan',
                        'created_default_schedule' => 'Buat Jadwal Default',
                        'updated_default_schedule' => 'Update Jadwal Default',
                        'deleted_default_schedule' => 'Hapus Jadwal Default',
                        'created_special_schedule' => 'Buat Jadwal Khusus',
                        'updated_special_schedule' => 'Update Jadwal Khusus',
                        'deleted_special_schedule' => 'Hapus Jadwal Khusus',
                    ])
                    ->label('Filter Aksi'),
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Filter Admin'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->label('Filter Tanggal'),
            ])
            ->actions([
                
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                ]),
            ])
            ->defaultSort('created_at', 'desc'); 
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }    
}