<?php

namespace App\Filament\Admin\Resources\HistoryPageResource\Pages;

use App\Filament\Admin\Resources\HistoryPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHistoryPages extends ListRecords
{
    protected static string $resource = HistoryPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
