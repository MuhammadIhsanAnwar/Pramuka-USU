<?php

namespace App\Filament\Admin\Resources\NewsCategoryResource\Pages;

use App\Filament\Admin\Resources\NewsCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsCategory extends CreateRecord
{
    protected static string $resource = NewsCategoryResource::class;
    protected static ?string $title = 'Buat Kategori Berita';
}