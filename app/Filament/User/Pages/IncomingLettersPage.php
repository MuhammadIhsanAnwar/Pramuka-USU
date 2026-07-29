<?php

namespace App\Filament\User\Pages;

use App\Filament\Concerns\YearFilterable;
use App\Models\IncomingLetter;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use UnitEnum;

class IncomingLettersPage extends Page implements HasTable
{
    use InteractsWithTable;
    use YearFilterable;

    protected function getTableHeader(): View | Htmlable | null
    {
        return $this->getYearFilterHeader();
    }

    protected function getTableQuery(): Builder | Relation
    {
        return $this->applyYearFilter(
            IncomingLetter::query()->latest('letter_date'),
            'letter_date',
        );
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('letter_date')
                ->label('Tanggal Surat')
                ->date()
                ->sortable(),
            TextColumn::make('sender')
                ->label('Asal Surat')
                ->searchable(),
            TextColumn::make('letter_number')
                ->label('Nomor Surat')
                ->searchable(),
            TextColumn::make('classification')
                ->label('Klasifikasi')
                ->searchable(),
            TextColumn::make('attachment')
                ->label('Lampiran')
                ->searchable(),
            TextColumn::make('subject')
                ->label('Perihal')
                ->wrap()
                ->searchable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [];
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }

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
