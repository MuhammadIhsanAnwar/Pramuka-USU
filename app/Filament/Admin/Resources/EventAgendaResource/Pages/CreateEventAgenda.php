<?php

namespace App\Filament\Admin\Resources\EventAgendaResource\Pages;

use App\Filament\Admin\Resources\EventAgendaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEventAgenda extends CreateRecord
{
    protected static string $resource = EventAgendaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}