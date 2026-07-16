<?php

namespace App\Filament\Admin\Resources\AboutGroupResource\Pages;

use App\Filament\Admin\Resources\AboutGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAboutGroups extends ListRecords
{
    protected static string $resource = AboutGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Buat Grup Tim')];
    }
}
