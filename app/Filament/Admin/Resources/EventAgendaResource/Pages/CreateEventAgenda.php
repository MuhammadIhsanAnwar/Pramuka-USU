<?php

namespace App\Filament\Admin\Resources\EventAgendaResource\Pages;

use App\Filament\Admin\Resources\EventAgendaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateEventAgenda extends CreateRecord
{
    protected static string $resource = EventAgendaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        return $data;
    }
}