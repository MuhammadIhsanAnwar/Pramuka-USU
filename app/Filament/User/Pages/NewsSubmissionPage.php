<?php

namespace App\Filament\User\Pages;

use App\Models\NewsCategory;
use App\Models\NewsPost;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use UnitEnum;

class NewsSubmissionPage extends Page
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $title = 'Kirim Berita';
    protected static ?string $slug = 'berita-kirim';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Kirim Berita';
    protected static string | UnitEnum | null $navigationGroup = 'User';
    protected string $view = 'filament.user.pages.news-submission';

    public ?string $newsTitle = null;
    public ?string $newsCategoryId = null;
    public ?string $summary = null;
    public array $content = [];
    public array $thumbnailPath = [];
    public array $imagePaths = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Berita')
                    ->description('Isi judul, ringkasan, dan foto sampul berita.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('newsTitle')
                                    ->label('Judul Berita')
                                    ->required()
                                    ->maxLength(255),
                                Select::make('newsCategoryId')
                                    ->label('Kategori')
                                    ->options(fn (): array => NewsCategory::query()
                                        ->active()
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->all())
                                    ->searchable()
                                    ->required()
                                    ->validationMessages([
                                        'required' => 'Kategori berita wajib dipilih.',
                                    ]),
                                FileUpload::make('thumbnailPath')
                                    ->label('Foto Sampul')
                                    ->image()
                                    ->directory('berita')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(10240)
                                    ->required(),
                                FileUpload::make('imagePaths')
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
                            ]),
                        Textarea::make('summary')
                            ->label('Ringkasan')
                            ->rows(3)
                            ->required()
                            ->maxLength(500),
                        RichEditor::make('content')
                            ->label('Isi Berita')
                            ->required()
                            ->fileAttachmentsDirectory('berita/isi')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsVisibility('public')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'undo',
                            ]),
                    ])
                    ->collapsible(false),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        validator($data, [
            'newsTitle' => ['required', 'string', 'max:255'],
            'newsCategoryId' => ['required', 'uuid', 'exists:news_categories,id'],
            'summary' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'thumbnailPath' => ['required', 'array', 'min:1'],
            'imagePaths' => ['nullable', 'array', 'max:4'],
            'imagePaths.*' => ['string'],
        ])->validate();

        $thumbnailPath = $data['thumbnailPath'] ?? null;
        $thumbnailPath = is_array($thumbnailPath) ? ($thumbnailPath[0] ?? null) : $thumbnailPath;

        NewsPost::create([
            'news_category_id' => $data['newsCategoryId'],
            'author_id' => Auth::id(),
            'title' => $data['newsTitle'],
            'excerpt' => $data['summary'],
            'content' => $data['content'],
            'thumbnail_path' => $thumbnailPath,
            'image_paths' => $data['imagePaths'] ?? [],
            'status' => 'draft',
            'slug' => Str::slug($data['newsTitle']).'-'.time(),
        ]);

        Notification::make()
            ->title('Berita berhasil dikirim')
            ->success()
            ->body('Berita Anda berhasil disimpan dan menunggu persetujuan admin.')
            ->send();

        $this->reset(['newsTitle', 'newsCategoryId', 'summary', 'content', 'thumbnailPath', 'imagePaths']);
    }
}
