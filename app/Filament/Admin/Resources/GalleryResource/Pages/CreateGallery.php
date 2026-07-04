<?php

namespace App\Filament\Admin\Resources\GalleryResource\Pages;

use App\Filament\Admin\Resources\GalleryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateGallery extends CreateRecord
{
    protected static string $resource = GalleryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = Auth::id();

        return $data;
    }
}