<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

     protected function getHeaderActions(): array
    {
        return [
            Action::make('Print')
                ->label('🖨️ Print')
                ->action(fn () => null) // Tidak ada aksi backend
                ->extraAttributes(['onclick' => 'window.print()'])
                ->color('gray')
        ];
    }




}
