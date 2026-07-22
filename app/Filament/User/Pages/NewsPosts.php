<?php

namespace App\Filament\User\Pages;

use App\Models\NewsPost;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;

class NewsPosts extends Page
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Berita';
    protected static ?string $slug = 'berita';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Berita';
    protected static string | UnitEnum | null $navigationGroup = 'User';

    public NewsPost $newsPost;

    public function mount(): void
    {
        $this->authorizeAccess();
        $this->newsPost = new NewsPost();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('news_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('thumbnail_path')
                    ->label('Thumbnail')
                    ->image()
                    ->directory('berita')
                    ->disk('public')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(10240)
                    ->required(),
                FileUpload::make('image_paths')
                    ->label('Foto Tambahan')
                    ->image()
                    ->multiple()
                    ->maxFiles(4)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->directory('berita')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(10240)
                    ->helperText('Upload sampai 4 foto tambahan. Total maksimal 5 foto termasuk thumbnail.'),
                DateTimePicker::make('published_at')
                    ->label('Tanggal Publish')
                    ->timezone('Asia/Jakarta'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draf',
                        'publish' => 'Terbit',
                    ])
                    ->required()
                    ->default('draft'),
                Textarea::make('excerpt')
                    ->label('Ringkasan')
                    ->columnSpanFull()
                    ->maxLength(500),
                RichEditor::make('content')
                    ->label('Isi Berita')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    protected function getFormModel(): string | object
    {
        return $this->newsPost;
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $data['author_id'] = Auth::id();
        $newsPost = NewsPost::create($data);

        Notification::make()
            ->title('Berita terkirim')
            ->success()
            ->body('Berita Anda telah disimpan dan akan ditinjau oleh admin.')
            ->send();

        $this->redirect('/user/berita');
    }
}
