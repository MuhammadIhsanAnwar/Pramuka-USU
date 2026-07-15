<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\IncomingLetterResource\Pages;
use App\Models\IncomingLetter;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class IncomingLetterResource extends Resource
{
    protected static ?string $model = IncomingLetter::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Kegiatan';

    protected static ?string $navigationLabel = 'Surat Masuk';
    
    // Ensure Filament shows the label consistently
    protected static ?string $label = 'Surat Masuk';
    protected static ?string $pluralLabel = 'Surat Masuk';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                DatePicker::make('letter_date')
                    ->label('Tanggal Surat')
                    ->required(),
                TextInput::make('sender')
                    ->label('Asal Surat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('letter_number')
                    ->label('Nomor Surat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('classification')
                    ->label('Klasifikasi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('attachment')
                    ->label('Lampiran')
                    ->required()
                    ->maxLength(255),
                Textarea::make('subject')
                    ->label('Perihal')
                    ->required()
                    ->columnSpanFull()
                    ->rows(4),
                FileUpload::make('file_path')
                    ->label('Upload File')
                    ->directory('incoming-letters')
                    ->visibility('public')
                    ->acceptedFileTypes(['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->maxSize(4096)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncomingLetters::route('/'),
            'create' => Pages\CreateIncomingLetter::route('/create'),
            'edit' => Pages\EditIncomingLetter::route('/{record}/edit'),
        ];
    }
}
