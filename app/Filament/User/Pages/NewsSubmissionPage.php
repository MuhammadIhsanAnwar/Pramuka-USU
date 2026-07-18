<?php

namespace App\Filament\User\Pages;

use App\Models\NewsPost;
use Filament\Actions\ButtonAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use BackedEnum;
use UnitEnum;

class NewsSubmissionPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Kirim Berita';
    protected static ?string $slug = 'berita-kirim';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Kirim Berita';
    protected static string | UnitEnum | null $navigationGroup = 'User';

    protected function getFormSchema(): array
    {
        return [
            Section::make('Berita')->schema([
                TextInput::make('news_title')
                    ->label('Judul Berita')
                    ->required()
                    ->maxLength(255),

                Textarea::make('summary')
                    ->label('Ringkasan')
                    ->rows(3)
                    ->required()
                    ->maxLength(500),

                Textarea::make('content')
                    ->label('Konten Berita')
                    ->rows(8)
                    ->required(),

                Toggle::make('allow_comments')
                    ->label('Izinkan komentar')
                    ->default(true),
            ]),
        ];
    }

    public function mount(): void
    {
        $this->form->fill([]);
    }

    protected function getActions(): array
    {
        return [
            ButtonAction::make('submit')
                ->label('Kirim Berita')
                ->action('submit')
                ->color('primary'),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        NewsPost::create([
            'user_id' => Auth::id(),
            'title' => $data['news_title'],
            'summary' => $data['summary'],
            'content' => $data['content'],
            'allow_comments' => $data['allow_comments'] ?? true,
            'status' => 'draft',
            'slug' => Str::slug($data['news_title']).'-'.time(),
        ]);

        $this->notify('success', 'Berita berhasil dikirim dan menunggu persetujuan admin.');

        $this->form->fill([]);
    }
}
