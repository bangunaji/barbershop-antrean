<?php

namespace App\Filament\Resources\DefaultOperatingHourResource\Pages;

use App\Filament\Resources\DefaultOperatingHourResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDefaultOperatingHours extends ListRecords
{
    protected static string $resource = DefaultOperatingHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
