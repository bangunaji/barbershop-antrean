<?php

namespace App\Filament\Resources\SpecialOperatingHourResource\Pages;

use App\Filament\Resources\SpecialOperatingHourResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpecialOperatingHours extends ListRecords
{
    protected static string $resource = SpecialOperatingHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
