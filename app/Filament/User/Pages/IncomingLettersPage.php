<?php

namespace App\Filament\User\Pages;

use App\Models\IncomingLetter;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class IncomingLettersPage extends Page
{
    protected static ?string $title = 'Surat Masuk';
    protected static ?string $slug = 'surat-masuk';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Surat Masuk';
    protected static string | UnitEnum | null $navigationGroup = 'User';
    protected string $view = 'filament.user.pages.incoming-letters';

    public function getViewData(): array
    {
        return [
            'letters' => IncomingLetter::query()
                ->latest('letter_date')
                ->get(),
        ];
    }
}
