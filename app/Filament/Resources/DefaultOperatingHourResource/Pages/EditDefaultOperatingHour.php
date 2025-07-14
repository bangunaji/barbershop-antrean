<?php

namespace App\Filament\Resources\DefaultOperatingHourResource\Pages;

use App\Filament\Resources\DefaultOperatingHourResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDefaultOperatingHour extends EditRecord
{
    protected static string $resource = DefaultOperatingHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
