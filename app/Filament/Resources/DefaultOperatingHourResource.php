<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DefaultOperatingHourResource\Pages;
use App\Models\DefaultOperatingHour;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Tables\Columns\BadgeColumn; 
use Illuminate\Support\Carbon; 


class DefaultOperatingHourResource extends Resource
{
    protected static ?string $model = DefaultOperatingHour::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Jadwal Default';
    protected static ?string $pluralModelLabel = 'Jadwal Default';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day_of_week')
                    ->label('Hari dalam Seminggu')
                    ->options([
                        0 => 'Minggu',
                        1 => 'Senin',
                        2 => 'Selasa',
                        3 => 'Rabu',
                        4 => 'Kamis',
                        5 => 'Jumat',
                        6 => 'Sabtu',
                    ])
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Pilih hari untuk mengatur jam operasional default.'),
                
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
                            if (!$get('is_closed') && $get('open_time') && $value && Carbon::parse($value)->lte(Carbon::parse($get('open_time')))) { 
                                $fail('Jam tutup harus setelah jam buka.');
                            }
                        },
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('Hari')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'Minggu',
                        1 => 'Senin',
                        2 => 'Selasa',
                        3 => 'Rabu',
                        4 => 'Kamis',
                        5 => 'Jumat',
                        6 => 'Sabtu',
                        default => 'Unknown',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('open_time')
                    ->label('Jam Buka')
                    ->time('H:i') 
                    ->default('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('close_time')
                    ->label('Jam Tutup')
                    ->time('H:i') 
                    ->default('-')
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

                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime(format: 'd F Y, H:i:s') 
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListDefaultOperatingHours::route('/'),
            'create' => Pages\CreateDefaultOperatingHour::route('/create'),
            'edit' => Pages\EditDefaultOperatingHour::route('/{record}/edit'),
        ];
    }    
}