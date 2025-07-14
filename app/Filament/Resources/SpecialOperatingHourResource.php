<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialOperatingHourResource\Pages;
use App\Models\SpecialOperatingHour;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Tables\Columns\BadgeColumn; 
use Illuminate\Support\Carbon; 

class SpecialOperatingHourResource extends Resource
{
    protected static ?string $model = SpecialOperatingHour::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Pengecualian Jadwal';
    protected static ?string $pluralModelLabel = 'Pengecualian Jadwal';

    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal Pengecualian')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->minDate(now()),
                
                Forms\Components\Toggle::make('is_closed')
                    ->label('Tutup Sepanjang Hari?')
                    ->live()
                    ->helperText('Aktifkan jika barbershop tutup pada hari ini secara default.'),

                Forms\Components\TimePicker::make('open_time')
                    ->label('Jam Buka')
                    ->seconds(false)
                    ->required(fn (Get $get) => !$get('is_closed'))
                    ->visible(fn (Get $get) => !$get('is_closed')),

                Forms\Components\TimePicker::make('close_time')
                    ->label('Jam Tutup')
                    ->seconds(false)
                    ->required(fn (Get $get) => !$get('is_closed'))
                    ->visible(fn (Get $get) => !$get('is_closed'))
                    ->rules([
                        fn (Forms\Get $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                            if (!$get('is_closed') && $get('open_time') && $value && \Illuminate\Support\Carbon::parse($value)->lte(\Illuminate\Support\Carbon::parse($get('open_time')))) {
                                $fail('Jam tutup harus setelah jam buka.');
                            }
                        },
                    ]),
                
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan Pengecualian (opsional)')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->formatStateUsing(fn (\Illuminate\Support\Carbon $state): string => $state->translatedFormat('d F Y'))
                    ->sortable(),
                
                
                Tables\Columns\TextColumn::make('open_time')
                    ->label('Jam Buka')
                    ->formatStateUsing(fn (?\Illuminate\Support\Carbon $state) => $state ? $state->format('H:i') : '-') 
                    ->sortable(),
                Tables\Columns\TextColumn::make('close_time')
                    ->label('Jam Tutup')
                    ->formatStateUsing(fn (?\Illuminate\Support\Carbon $state) => $state ? $state->format('H:i') : '-') 
                    ->sortable(),
                
                
                BadgeColumn::make('is_closed')
                    ->label('Status')
                    ->colors([
                        'danger' => fn (bool $state) => $state === true,
                        'success' => fn (bool $state) => $state === false,
                    ])
                    ->formatStateUsing(fn (bool $state) => $state ? 'Tutup' : 'Buka')
                    ->icons([
                        'heroicon-o-x-circle' => fn (bool $state) => $state === true,
                        'heroicon-o-check-circle' => fn (bool $state) => $state === false,
                    ]),

                Tables\Columns\TextColumn::make('notes')
                    ->limit(50)
                    ->label('Catatan')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from_date')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('to_date')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['to_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
                    ->label('Filter Tanggal'),
                Tables\Filters\TernaryFilter::make('is_closed')
                    ->label('Tampilkan')
                    ->trueLabel('Tutup')
                    ->falseLabel('Jam Khusus')
                    ->placeholder('Semua Pengecualian'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListSpecialOperatingHours::route('/'),
            'create' => Pages\CreateSpecialOperatingHour::route('/create'),
            'edit' => Pages\EditSpecialOperatingHour::route('/{record}/edit'),
        ];
    }    
}