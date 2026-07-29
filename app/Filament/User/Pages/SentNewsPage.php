<?php

namespace App\Filament\User\Pages;

use App\Filament\Concerns\YearFilterable;
use App\Models\NewsPost;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class SentNewsPage extends Page implements HasTable
{
    use InteractsWithTable;
    use YearFilterable;

    protected function getTableHeader(): View | Htmlable | null
    {
        return $this->getYearFilterHeader();
    }

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Berita Terkirim';
    protected static ?string $slug = 'berita-terkirim';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Berita Terkirim';
    protected static string | UnitEnum | null $navigationGroup = 'User';
    protected string $view = 'filament.user.pages.sent-news';

    protected function getTableQuery(): Builder | Relation
    {
        return $this->applyYearFilter(
            NewsPost::query()
                ->where('author_id', Auth::id())
                ->with('category')
                ->latest('created_at'),
            'published_at',
        );
    }

    protected function getTableColumns(): array
    {
        return [
            ImageColumn::make('thumbnail_path')
                ->label('Thumbnail')
                ->square(),
            TextColumn::make('title')
                ->label('Judul')
                ->searchable()
                ->sortable()
                ->limit(40),
            TextColumn::make('category.name')
                ->label('Kategori')
                ->badge(),
            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->colors([
                    'gray' => 'draft',
                    'success' => 'publish',
                ])
                ->formatStateUsing(fn (?string $state): ?string => match ($state) {
                    'draft' => 'Draf',
                    'publish' => 'Terbit',
                    default => $state,
                }),
            TextColumn::make('published_at')
                ->label('Tanggal Terbit')
                ->dateTime('d F Y H:i:s')
                ->sortable(),
            TextColumn::make('viewer_count')
                ->label('Jumlah Pengunjung')
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (NewsPost $record): bool => $record->status === 'draft'),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }
}
