<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking; 
use App\Models\Service;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Carbon; 
use Livewire\Attributes\Url;
use App\Models\DefaultOperatingHour;
use App\Models\SpecialOperatingHour;
use App\Models\ActivityLog; 


class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Manajemen Booking';
    protected static ?string $pluralModelLabel = 'Booking';

    #[Url]
    public ?array $tableFilters = null;
    #[Url]
    public ?string $tableSearch = '';
    #[Url]
    public ?string $tableSortColumn = null;
    #[Url]
    public ?string $tableSortDirection = null;

    public static function getShopHoursForDate(Carbon $date): object
    {
        $specialHours = SpecialOperatingHour::where('date', $date->toDateString())->first();
        if ($specialHours) {
            return (object) [
                'is_closed' => $specialHours->is_closed,
                'open_time' => $specialHours->open_time ? Carbon::parse($specialHours->open_time->format('H:i:s'), config('app.timezone')) : null,
                'close_time' => $specialHours->close_time ? Carbon::parse($specialHours->close_time->format('H:i:s'), config('app.timezone')) : null,
            ];
        }

        $defaultHours = DefaultOperatingHour::where('day_of_week', $date->dayOfWeek)->first();
        if ($defaultHours) {
            return (object) [
                'is_closed' => $defaultHours->is_closed,
                'open_time' => $defaultHours->open_time ? Carbon::parse($defaultHours->open_time->format('H:i:s'), config('app.timezone')) : null,
                'close_time' => $defaultHours->close_time ? Carbon::parse($defaultHours->close_time->format('H:i:s'), config('app.timezone')) : null,
            ];
        }

        return (object) [
            'is_closed' => true,
            'open_time' => null,
            'close_time' => null,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelanggan')
                    ->schema([
                        Forms\Components\ToggleButtons::make('booking_type')
                            ->label('Jenis Pelanggan')
                            ->options([
                                'online' => 'Booking Online',
                                'walk-in' => 'Walk-in (Datang Langsung)',
                            ])
                            ->default('online')
                            ->required()
                            ->live()
                            ->inline(),

                        Forms\Components\Select::make('user_id')
                            ->label('Pilih Pelanggan Terdaftar')
                            ->relationship('user', 'name', modifyQueryUsing: fn (Builder $query) => $query->where('role', 'user'))
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get) => $get('booking_type') === 'online')
                            ->visible(fn (Get $get) => $get('booking_type') === 'online')
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                if ($state) {
                                    $user = User::find($state);
                                    if ($user) {
                                        $set('customer_name', $user->name);
                                        $set('customer_phone', $user->phone ?? null);
                                    }
                                } else {
                                    $set('customer_name', null);
                                    $set('customer_phone', null);
                                }
                                $set('booking_date', $get('booking_date'));
                            })
                            ->rules([
                                function (Get $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        if (empty($value)) {
                                            return;
                                        }

                                        $existingActiveBooking = Booking::where('user_id', $value)
                                                                        ->where('booking_status', 'active')
                                                                        ->exists();
                                        if ($existingActiveBooking) {
                                            $fail('Pelanggan ini sudah memiliki booking aktif dan tidak dapat membuat booking baru.');
                                        }
                                    };
                                },
                            ])
                            ->hint('Jika tidak ada dalam daftar, buat booking sebagai "Walk-in".'),

                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nama Pelanggan')
                            ->required(fn (Get $get) => $get('booking_type') === 'walk-in')
                            ->visible(fn (Get $get) => $get('booking_type') === 'walk-in')
                            ->rules([
                                function (Get $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        if ($get('booking_type') === 'walk-in' && !empty($value)) {
                                            $existingActiveWalkin = Booking::where('customer_name', $value)
                                                                            ->where('booking_type', 'walk-in')
                                                                            ->where('booking_status', 'active')
                                                                            ->exists();
                                            if ($existingActiveWalkin) {
                                                $fail('Pelanggan walk-in dengan nama ini sudah memiliki booking aktif. Gunakan nama lain atau selesaikan booking sebelumnya.');
                                            }
                                        }
                                    };
                                },
                            ]),

                        Forms\Components\TextInput::make('customer_phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->nullable()
                            ->visible(fn (Get $get) => $get('booking_type') === 'walk-in'),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Pesanan')
                    ->schema([
                        Forms\Components\CheckboxList::make('services')
                            ->relationship('services', 'name')
                            ->required()
                            ->label('Layanan yang Dipilih')
                            ->columns(2)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $services = Service::find($get('services'));
                                $set('total_price', $services->sum('price'));
                                $set('total_duration_minutes', $services->sum('duration_minutes'));
                            }),
                        
                        Forms\Components\TextInput::make('total_price')
                            ->prefix('Rp')
                            ->readOnly()
                            ->numeric()
                            ->label('Total Harga'),

                        Forms\Components\Hidden::make('total_duration_minutes'),

                        Forms\Components\DatePicker::make('booking_date')
                            ->default(now())
                            ->required()
                            ->minDate(today())
                            ->rules([
                                function (Get $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        $date = Carbon::parse($value, config('app.timezone'));
                                        $shopHours = self::getShopHoursForDate($date);
                                        
                                        if ($shopHours->is_closed) {
                                            $fail('Barbershop tutup pada tanggal ini.');
                                        }
                                    };
                                },
                            ])
                            ->live(),

                        Forms\Components\TimePicker::make('booking_time')
                            ->label('Jam Booking')
                            ->seconds(false)
                            ->nullable()
                            ->default(now()->addHour()->startOfHour())
                            ->rules([
                                function (Get $get) use ($form) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get, $form) {
                                        $selectedDateString = $get('booking_date');
                                        if (!$selectedDateString || is_null($value)) {
                                            return;
                                        }
                                        
                                        $selectedDate = Carbon::parse($selectedDateString, config('app.timezone'));
                                        $inputTime = Carbon::parse($value);
                                        $dateTimeInput = $selectedDate->copy()->setTime($inputTime->hour, $inputTime->minute, $inputTime->second);
                                        
                                        $shopHours = self::getShopHoursForDate($selectedDate);

                                        if ($shopHours->is_closed) {
                                            $fail('Barbershop tutup pada tanggal ini.');
                                            return;
                                        }
                                        
                                        if ($shopHours->open_time && $shopHours->close_time) {
                                            $openTimeFull = $selectedDate->copy()->setTime($shopHours->open_time->hour, $shopHours->open_time->minute, $shopHours->open_time->second);
                                            $closeTimeFull = $selectedDate->copy()->setTime($shopHours->close_time->hour, $shopHours->close_time->minute, $shopHours->close_time->second);

                                            if (!$dateTimeInput->between($openTimeFull, $closeTimeFull, true)) {
                                                $fail("Jam booking harus antara {$shopHours->open_time->format('H:i')} dan {$shopHours->close_time->format('H:i')}.");
                                            }
                                        } else {
                                            $fail('Jam operasional tidak ditemukan untuk tanggal ini. Mohon atur di pengaturan Jadwal Default.');
                                        }
                                        
                                        $currentTime = Carbon::now(config('app.timezone'));
                                        if ($selectedDate->isToday() && $dateTimeInput->lt($currentTime)) {
                                            $fail('Jam booking tidak bisa di masa lalu.');
                                        }

                                        if (!$form->getRecord()) {
                                            $existingBookingAtSameTime = Booking::where('booking_date', $selectedDate->toDateString())
                                                                                ->whereTime('booking_time', $dateTimeInput->toTimeString())
                                                                                ->where('booking_status', 'active')
                                                                                ->exists();
                                            if ($existingBookingAtSameTime) {
                                                $fail('Sudah ada booking aktif pada tanggal dan jam ini. Mohon pilih waktu lain.');
                                            }
                                        }
                                    };
                                },
                            ])
                            ->live(),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan / Preferensi')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Antrean')
                    ->sortable()
                    ->alignCenter()
                    ->extraAttributes(['class' => 'font-bold text-lg']),
                
                Tables\Columns\TextColumn::make('queue_number')
                    ->label('Kode Antrean')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                
                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Tanggal Booking')
                
                ->formatStateUsing(fn (Carbon $state): string => $state->translatedFormat('l, d F Y')) 
                ->sortable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('booking_time')
                    ->label('Jam')
                    ->formatStateUsing(fn (?Carbon $state) => $state ? $state->format('H:i') : '-')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('booking_type')
                    ->label('Jenis')
                    ->colors([
                        'online' => 'success',
                        'walk-in' => 'warning',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'online' => 'Booking Online',
                        'walk-in' => 'Walk-in',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('arrival_status')
                    ->label('Kehadiran')
                    ->colors([
                        'primary' => 'waiting', 'success' => 'arrived', 'warning' => 'late',
                        'info' => 'completed', 'danger' => 'cancelled',
                    ])->formatStateUsing(fn ($state) => match($state) {
                        'waiting' => 'Menunggu', 'arrived' => 'Datang', 'late' => 'Terlambat',
                        'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', default => ucfirst($state),
                    }),
                
                Tables\Columns\TextColumn::make('services.name')->badge()->limit(2)->label('Layanan')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_price')->money('IDR')->label('Total Harga')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_duration_minutes')
                    ->label('Durasi Total')
                    ->suffix(' menit')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\BadgeColumn::make('booking_status')
                    ->label('Status Booking')
                    ->colors([
                        'active' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    ])->formatStateUsing(fn ($state) => match($state) {
                        'active' => 'Aktif', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', default => ucfirst($state),
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime(format: 'd F Y, H:i:s') 
                ->label('Diperbarui Pada')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('notes')
                    ->limit(50)
                    ->label('Catatan')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('sort_order', true)
            ->defaultSort('sort_order', 'asc')
            
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('update_status')
                        ->label('Perbarui Status')
                        ->icon('heroicon-o-pencil-square')
                        ->fillForm(fn (Booking $record): array => [
                            'status' => $record->arrival_status, 
                        ])
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Pilih Status Baru')
                                ->options([
                                    'waiting' => 'Menunggu',
                                    'arrived' => 'Datang',
                                    'late' => 'Terlambat',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Batalkan',
                                ])
                                ->required()
                                ->default('waiting'),
                        ])
                        ->action(function (Booking $record, array $data): void {
                            $newStatus = $data['status'];

                            $record->update([
                                'arrival_status' => $newStatus,
                                'booking_status' => in_array($newStatus, ['completed', 'cancelled']) ? $newStatus : 'active',
                            ]);

                            if (in_array($newStatus, ['completed', 'cancelled'])) {
                                $record->sort_order = null; 
                                $record->estimated_turn_time = null;
                                $record->saveQuietly();
                            }
                            
                            Booking::recalculateQueueNumbersAndSortOrder(
                                $record->booking_date->toDateString() 
                            );

                            \Filament\Notifications\Notification::make()
                                ->title('Status booking berhasil diperbarui.')
                                ->success()
                                ->send();
                        })
                        ->modalSubmitActionLabel('Perbarui'),
                    
                    Tables\Actions\EditAction::make(),
                ])
            ])
            ->filters([
            Tables\Filters\Filter::make('booking_date')
                ->form([
                    Forms\Components\DatePicker::make('date')
                        ->label('Pilih Tanggal')
                    ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('booking_date', $date),
                        );
                })
                ->indicateUsing(function (array $data): ?string {
                    if (! $data['date']) {
                        return null;
                    }
                    
                    return 'Tanggal: ' . Carbon::parse($data['date'])->translatedFormat('d F Y');
                }),
            ])
            ->reorderRecordsTriggerAction(
                fn (Tables\Actions\Action $action, bool $isReordering) => $action
                    ->label($isReordering ? 'Selesai Urutkan' : 'Urutkan Antrean')
                    ->icon('heroicon-o-arrows-up-down')
                    ->button()
            )
            ->recordUrl(
                fn (Booking $record): string => Pages\EditBooking::getUrl([$record]),
            )
            ->poll('5s');
    }

    protected static function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['booking_type'] === 'online') {
            if (!empty($data['user_id'])) {
                $user = User::find($data['user_id']);
                if ($user) {
                    $data['customer_name'] = $user->name;
                    $data['customer_phone'] = $user->phone ?? null;
                } else {
                    $data['customer_name'] = 'Registered User (Nama tidak ditemukan)';
                }
            } else {
                $data['customer_name'] = 'Online User (ID kosong)';
            }
        } elseif ($data['booking_type'] === 'walk-in') {
            $data['customer_name'] = $data['customer_name'] ?? 'Pelanggan Walk-in';
            $data['customer_phone'] = $data['customer_phone'] ?? null;
        } else {
            $data['customer_name'] = 'Pelanggan Tak Dikenal';
            $data['customer_phone'] = null;
        }

        if (isset($data['services'])) {
            $selectedServices = Service::whereIn('id', $data['services'])->get();
            $data['total_duration_minutes'] = $selectedServices->sum('duration_minutes');
        } else {
            $data['total_duration_minutes'] = 0;
        }
        
        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}