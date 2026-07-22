<?php

namespace App\Filament\User\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class NewsMainPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Kirim Berita';
    protected static ?string $slug = 'kirim-berita';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Kirim Berita';
    protected static string | UnitEnum | null $navigationGroup = 'User';
    protected string $view = 'filament.user.pages.news-main';
}
