<?php

namespace App\Filament\Pages\Settings;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class GeneralSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6tooth';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $title = 'Pengaturan Umum Barbershop';
    protected static ?string $slug = 'general-settings';

    protected static string $view = 'filament.pages.settings.general-settings';

    protected static bool $shouldRegisterNavigation = false; 
    

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'default_open_time' => Setting::where('key', 'default_open_time')->first()?->value ?? '09:00:00',
            'default_close_time' => Setting::where('key', 'default_close_time')->first()?->value ?? '18:00:00',
            'is_barbershop_closed_default' => Setting::where('key', 'is_barbershop_closed_default')->first()?->value === '1',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TimePicker::make('default_open_time')
                    ->label('Jam Buka Default')
                    ->seconds(false)
                    ->required()
                    ->helperText('Jam buka standar untuk hari-hari biasa.'),
                Forms\Components\TimePicker::make('default_close_time')
                    ->label('Jam Tutup Default')
                    ->seconds(false)
                    ->required()
                    ->helperText('Jam tutup standar untuk hari-hari biasa.')
                    ->rules([
                        fn (Forms\Get $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                            if ($get('default_open_time') && $value && \Illuminate\Support\Carbon::parse($value)->lte(\Illuminate\Support\Carbon::parse($get('default_open_time')))) {
                                $fail('Jam tutup default harus setelah jam buka default.');
                            }
                        },
                    ]),
                Forms\Components\Toggle::make('is_barbershop_closed_default')
                    ->label('Tutup Default Sepanjang Hari?')
                    ->helperText('Aktifkan jika barbershop biasanya tutup (contoh: hari Minggu). Ini akan diterapkan pada semua tanggal kecuali ada pengecualian.'),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            Setting::updateOrCreate(['key' => 'default_open_time'], ['value' => $data['default_open_time']]);
            Setting::updateOrCreate(['key' => 'default_close_time'], ['value' => $data['default_close_time']]);
            Setting::updateOrCreate(['key' => 'is_barbershop_closed_default'], ['value' => $data['is_barbershop_closed_default'] ? '1' : '0']);

            Notification::make()
                ->title('Pengaturan berhasil disimpan!')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Terjadi kesalahan saat menyimpan pengaturan.')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}