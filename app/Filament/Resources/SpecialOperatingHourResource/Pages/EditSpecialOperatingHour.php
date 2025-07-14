<?php

namespace App\Filament\Resources\SpecialOperatingHourResource\Pages;

use App\Filament\Resources\SpecialOperatingHourResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecialOperatingHour extends EditRecord
{
    protected static string $resource = SpecialOperatingHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
