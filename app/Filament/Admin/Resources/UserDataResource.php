<?php

namespace App\Filament\Admin\Resources;

use App\Enums\RoleName;
use App\Filament\Admin\Resources\UserDataResource\Pages;
use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Facades\Filament;
use Filament\Forms\Components\Card;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class UserDataResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Akun';

    protected static ?string $navigationLabel = 'Data Pengguna';

    public static function getPluralModelLabel(): string
    {
        return 'Data Pengguna';
    }

    public static function getSingularModelLabel(): string
    {
        return 'Data Pengguna';
    }

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('qr_code_url')
                    ->label('QR Code')
                    ->height(72)
                    ->width(72)
                    ->toggleable(false)
                    ->url(fn (?string $state, $record): ?string => $record?->qr_code_url),
                ImageColumn::make('avatar_url')
                    ->label('Foto')
                    ->height(80)
                    ->width(60)
                    ->defaultImageUrl(fn (): string => asset('images/default-avatar.png')),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-akun'),
                TextColumn::make('jenis_user')
                    ->label('Jenis Pengguna')
                    ->badge()
                    ->formatStateUsing(fn ($state): ?string => $state ? ucwords(str_replace('_', ' ', is_string($state) ? $state : $state->value)) : null)
                    ->sortable(),
                TextColumn::make('whatsapp_number')
                    ->label('Nomor WhatsApp')
                    ->searchable()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'biodata'),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->sortable()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'biodata'),
                TextColumn::make('religion')
                    ->label('Agama')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'biodata'),
                TextColumn::make('blood_type')
                    ->label('Golongan Darah')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'biodata'),
                TextColumn::make('hobby')
                    ->label('Hobi')
                    ->limit(20)
                    ->wrap()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'biodata'),
                TextColumn::make('siblings_count')
                    ->label('Jumlah Saudara')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'biodata'),
                TextColumn::make('whatsapp_number')
                    ->label('WhatsApp')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'biodata'),
                TextColumn::make('domisili_country')
                    ->label('Negara Domisili')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('domisili_province')
                    ->label('Provinsi Domisili')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('domisili_city')
                    ->label('Kota/Kabupaten Domisili')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('domisili_district')
                    ->label('Kecamatan Domisili')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('domisili_village')
                    ->label('Kelurahan/Desa Domisili')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('domisili_street')
                    ->label('Jalan Domisili')
                    ->limit(30)
                    ->wrap()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('asal_country')
                    ->label('Negara Asal')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('asal_province')
                    ->label('Provinsi Asal')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('asal_city')
                    ->label('Kota/Kabupaten Asal')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('asal_district')
                    ->label('Kecamatan Asal')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('asal_village')
                    ->label('Kelurahan/Desa Asal')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('asal_street')
                    ->label('Jalan Asal')
                    ->limit(30)
                    ->wrap()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'alamat'),
                TextColumn::make('education_status')
                    ->label('Status Pendidikan')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'riwayat-pendidikan'),
                TextColumn::make('nim')
                    ->label('NIM')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'riwayat-pendidikan'),
                TextColumn::make('kampus')
                    ->label('Kampus')
                    ->limit(25)
                    ->wrap()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'riwayat-pendidikan'),
                TextColumn::make('fakultas')
                    ->label('Fakultas')
                    ->limit(25)
                    ->wrap()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'riwayat-pendidikan'),
                TextColumn::make('program_studi')
                    ->label('Program Studi')
                    ->limit(30)
                    ->wrap()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'riwayat-pendidikan'),
                TextColumn::make('father_name')
                    ->label('Nama Ayah')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'orang-tua'),
                TextColumn::make('father_status')
                    ->label('Status Ayah')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'orang-tua'),
                TextColumn::make('father_phone')
                    ->label('HP Ayah')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'orang-tua'),
                TextColumn::make('mother_name')
                    ->label('Nama Ibu')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'orang-tua'),
                TextColumn::make('mother_status')
                    ->label('Status Ibu')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'orang-tua'),
                TextColumn::make('mother_phone')
                    ->label('HP Ibu')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'orang-tua'),
                TextColumn::make('guardian_name')
                    ->label('Nama Wali')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'orang-tua'),
                TextColumn::make('guardian_status')
                    ->label('Status Wali')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'orang-tua'),
                TextColumn::make('guardian_phone')
                    ->label('HP Wali')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'orang-tua'),
                TextColumn::make('satuan')
                    ->label('Satuan')
                    ->sortable()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-pramuka'),
                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-pramuka'),
                TextColumn::make('nta')
                    ->label('NTA')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-pramuka'),
                TextColumn::make('tahun_masuk_pramuka_usu')
                    ->label('Tahun Masuk Pramuka USU')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-pramuka'),
                TextColumn::make('nama_omantaru')
                    ->label('Nama Omantaru')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-pramuka'),
                TextColumn::make('golongan')
                    ->label('Golongan')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-pramuka'),
                TextColumn::make('tingkatan')
                    ->label('Tingkatan')
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-pramuka'),
                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->hidden(fn (?User $record): bool => Filament::auth()->id() === $record?->id)
                    ->disabled(fn (?User $record): bool => $record?->hasRole(RoleName::Admin->value))
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-akun'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d F Y H:i:s')
                    ->sortable()
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-akun'),
            ])
            ->actions([
                EditAction::make()
                    ->url(fn (User $record): string => UserResource::getUrl('edit', ['record' => $record]))
                    ->visible(fn (HasTable $livewire): bool => $livewire->activeTab === 'data-akun'),
                DeleteAction::make()
                    ->visible(fn (HasTable $livewire, User $record): bool => $livewire->activeTab === 'data-akun' && ! $record->hasRole(RoleName::Admin->value)),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserData::route('/'),
        ];
    }
}
