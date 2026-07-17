<?php

namespace App\Filament\User\Pages;

use App\Models\User;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    protected static ?string $title = 'Lengkapi Profil';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('profile_tabs')
                    ->tabs([
                        Tab::make('Biodata')
                            ->schema([
                                Section::make('Data Diri')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('data.name')
                                                    ->label('Nama')
                                                    ->disabled()
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.email')
                                                    ->label('Email')
                                                    ->email()
                                                    ->disabled()
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.birth_place')
                                                    ->label('Tempat Lahir')
                                                    ->required()
                                                    ->maxLength(255),
                                                DatePicker::make('data.birth_date')
                                                    ->label('Tanggal Lahir')
                                                    ->required(),
                                                Select::make('data.gender')
                                                    ->label('Jenis Kelamin')
                                                    ->options([
                                                        'Laki-laki' => 'Laki-laki',
                                                        'Perempuan' => 'Perempuan',
                                                    ])
                                                    ->required(),
                                                Select::make('data.religion')
                                                    ->label('Agama')
                                                    ->options([
                                                        'Islam' => 'Islam',
                                                        'Kristen' => 'Kristen',
                                                        'Katolik' => 'Katolik',
                                                        'Hindu' => 'Hindu',
                                                        'Buddha' => 'Buddha',
                                                        'Konghucu' => 'Konghucu',
                                                        'Lainnya' => 'Lainnya',
                                                    ])
                                                    ->required(),
                                                Select::make('data.blood_type')
                                                    ->label('Golongan Darah')
                                                    ->options([
                                                        'A' => 'A',
                                                        'B' => 'B',
                                                        'AB' => 'AB',
                                                        'O' => 'O',
                                                        '-' => 'Tidak Tahu',
                                                    ])
                                                    ->required(),
                                                TextInput::make('data.hobby')
                                                    ->label('Hobi')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.siblings_count')
                                                    ->label('Jumlah Saudara')
                                                    ->numeric()
                                                    ->required(),
                                                TextInput::make('data.whatsapp_number')
                                                    ->label('Nomor WhatsApp')
                                                    ->tel()
                                                    ->required()
                                                    ->maxLength(30),
                                                Select::make('data.marital_status')
                                                    ->label('Status Perkawinan')
                                                    ->options([
                                                        'Belum Kawin' => 'Belum Kawin',
                                                        'Kawin' => 'Kawin',
                                                        'Duda' => 'Duda',
                                                        'Janda' => 'Janda',
                                                    ])
                                                    ->required(),
                                                TextInput::make('data.job')
                                                    ->label('Pekerjaan')
                                                    ->required()
                                                    ->maxLength(255),
                                                FileUpload::make('data.avatar_path')
                                                    ->label('Upload Pas Foto')
                                                    ->image()
                                                    ->directory('avatars')
                                                    ->disk('public')
                                                    ->visibility('public')
                                                    ->maxSize(2048)
                                                    ->imageCropAspectRatio('3:4')
                                                    ->required(),
                                            ]),
                                    ]),
                            ]),
                        Tab::make('Alamat')
                            ->schema([
                                Section::make('Domisili')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('data.domisili_country')
                                                    ->label('Negara')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.domisili_province')
                                                    ->label('Provinsi')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.domisili_city')
                                                    ->label('Kota/Kabupaten')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.domisili_district')
                                                    ->label('Kecamatan')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.domisili_village')
                                                    ->label('Kelurahan/Desa')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.domisili_rt')
                                                    ->label('RT')
                                                    ->required()
                                                    ->maxLength(10),
                                                TextInput::make('data.domisili_rw')
                                                    ->label('RW')
                                                    ->required()
                                                    ->maxLength(10),
                                                TextInput::make('data.domisili_postal_code')
                                                    ->label('Kode Pos')
                                                    ->required()
                                                    ->maxLength(10),
                                            ]),
                                        TextInput::make('data.domisili_street')
                                            ->label('Jalan')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                Section::make('Asal')
                                    ->schema([
                                        Checkbox::make('data.same_as_domisili')
                                            ->label('Samakan dengan Domisili')
                                            ->reactive()
                                            ->afterStateUpdated(function (callable $set, $state) {
                                                if ($state) {
                                                    $set('data.asal_country', data_get($this->data, 'data.domisili_country'));
                                                    $set('data.asal_province', data_get($this->data, 'data.domisili_province'));
                                                    $set('data.asal_city', data_get($this->data, 'data.domisili_city'));
                                                    $set('data.asal_district', data_get($this->data, 'data.domisili_district'));
                                                    $set('data.asal_village', data_get($this->data, 'data.domisili_village'));
                                                    $set('data.asal_rt', data_get($this->data, 'data.domisili_rt'));
                                                    $set('data.asal_rw', data_get($this->data, 'data.domisili_rw'));
                                                    $set('data.asal_postal_code', data_get($this->data, 'data.domisili_postal_code'));
                                                    $set('data.asal_street', data_get($this->data, 'data.domisili_street'));
                                                }
                                            }),
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('data.asal_country')
                                                    ->label('Negara')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.asal_province')
                                                    ->label('Provinsi')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.asal_city')
                                                    ->label('Kota/Kabupaten')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.asal_district')
                                                    ->label('Kecamatan')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.asal_village')
                                                    ->label('Kelurahan/Desa')
                                                    ->required()
                                                    ->maxLength(255),
                                                TextInput::make('data.asal_rt')
                                                    ->label('RT')
                                                    ->required()
                                                    ->maxLength(10),
                                                TextInput::make('data.asal_rw')
                                                    ->label('RW')
                                                    ->required()
                                                    ->maxLength(10),
                                                TextInput::make('data.asal_postal_code')
                                                    ->label('Kode Pos')
                                                    ->required()
                                                    ->maxLength(10),
                                            ]),
                                        TextInput::make('data.asal_street')
                                            ->label('Jalan')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                            ]),
                        Tab::make('Riwayat Pendidikan')
                            ->schema([
                                Section::make('Pendidikan')
                                    ->schema([
                                        Select::make('data.education_status')
                                            ->label('Status')
                                            ->options([
                                                'Mahasiswa' => 'Mahasiswa',
                                                'Alumni' => 'Alumni',
                                                'Bukan Mahasiswa' => 'Bukan Mahasiswa',
                                            ])
                                            ->required()
                                            ->reactive(),
                                        TextInput::make('data.nim')
                                            ->label('NIM')
                                            ->required(fn (callable $get) => $get('data.education_status') !== 'Bukan Mahasiswa')
                                            ->maxLength(100),
                                        TextInput::make('data.kampus')
                                            ->label('Kampus')
                                            ->required(fn (callable $get) => $get('data.education_status') !== 'Bukan Mahasiswa')
                                            ->maxLength(255),
                                        TextInput::make('data.fakultas')
                                            ->label('Fakultas')
                                            ->required(fn (callable $get) => $get('data.education_status') !== 'Bukan Mahasiswa')
                                            ->maxLength(255),
                                        TextInput::make('data.program_studi')
                                            ->label('Program Studi')
                                            ->required(fn (callable $get) => $get('data.education_status') !== 'Bukan Mahasiswa')
                                            ->maxLength(255),
                                    ]),
                            ]),
                        Tab::make('Orang Tua')
                            ->schema([
                                Section::make('Ayah')
                                    ->schema([
                                        TextInput::make('data.father_name')
                                            ->label('Nama')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('data.father_status')
                                            ->label('Status')
                                            ->options([
                                                'Hidup' => 'Hidup',
                                                'Meninggal' => 'Meninggal',
                                            ])
                                            ->required(),
                                        Textarea::make('data.father_address')
                                            ->label('Alamat Lengkap')
                                            ->required()
                                            ->rows(3),
                                        TextInput::make('data.father_phone')
                                            ->label('Nomor Telepon')
                                            ->tel()
                                            ->required()
                                            ->maxLength(30),
                                    ]),
                                Section::make('Ibu')
                                    ->schema([
                                        TextInput::make('data.mother_name')
                                            ->label('Nama')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('data.mother_status')
                                            ->label('Status')
                                            ->options([
                                                'Hidup' => 'Hidup',
                                                'Meninggal' => 'Meninggal',
                                            ])
                                            ->required(),
                                        Textarea::make('data.mother_address')
                                            ->label('Alamat Lengkap')
                                            ->required()
                                            ->rows(3),
                                        TextInput::make('data.mother_phone')
                                            ->label('Nomor Telepon')
                                            ->tel()
                                            ->required()
                                            ->maxLength(30),
                                    ]),
                                Section::make('Wali')
                                    ->schema([
                                        TextInput::make('data.guardian_name')
                                            ->label('Nama')
                                            ->maxLength(255),
                                        Select::make('data.guardian_status')
                                            ->label('Status')
                                            ->options([
                                                'Hidup' => 'Hidup',
                                                'Meninggal' => 'Meninggal',
                                            ]),
                                        Textarea::make('data.guardian_address')
                                            ->label('Alamat Lengkap')
                                            ->rows(3),
                                        TextInput::make('data.guardian_phone')
                                            ->label('Nomor Telepon')
                                            ->tel()
                                            ->maxLength(30),
                                    ]),
                            ]),
                        Tab::make('Data Pramuka')
                            ->schema([
                                Section::make('Pramuka')
                                    ->schema([
                                        Select::make('data.satuan')
                                            ->label('Satuan')
                                            ->options([
                                                'Majelis Pembimbing Gugus Depan Gerakan Pramuka Kota Medan 08-137 dan 08-138' => 'Majelis Pembimbing Gugus Depan Gerakan Pramuka Kota Medan 08-137 dan 08-138',
                                                'Gugus Depan Gerakan Pramuka Kota Medan 08-137' => 'Gugus Depan Gerakan Pramuka Kota Medan 08-137',
                                                'Gugus Depan Gerakan Pramuka Kota Medan 08-138' => 'Gugus Depan Gerakan Pramuka Kota Medan 08-138',
                                                'Racana Soetan Koemala Pontas' => 'Racana Soetan Koemala Pontas',
                                                'Racana Rasuna Said' => 'Racana Rasuna Said',
                                                'Ambalan Soetan Koemala Pontas' => 'Ambalan Soetan Koemala Pontas',
                                                'Ambalan Rasuna Said' => 'Ambalan Rasuna Said',
                                            ])
                                            ->required(),
                                        TextInput::make('data.jabatan')
                                            ->label('Jabatan')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('data.nta')
                                            ->label('NTA')
                                            ->required()
                                            ->maxLength(50),
                                        TextInput::make('data.tahun_masuk_pramuka_usu')
                                            ->label('Tahun Masuk Pramuka USU')
                                            ->numeric()
                                            ->required(),
                                        TextInput::make('data.nama_omantaru')
                                            ->label('Nama OMANTARU')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('data.golongan')
                                            ->label('Golongan')
                                            ->options([
                                                'Pembina' => 'Pembina',
                                                'Pandega' => 'Pandega',
                                                'Penegak' => 'Penegak',
                                            ])
                                            ->required(),
                                        Select::make('data.tingkatan')
                                            ->label('Tingkatan')
                                            ->options([
                                                'KPD' => 'KPD',
                                                'KPL' => 'KPL',
                                                'KMD' => 'KMD',
                                                'KML' => 'KML',
                                                'Calon Pandega' => 'Calon Pandega',
                                                'Pandega' => 'Pandega',
                                                'Calon Penegak' => 'Calon Penegak',
                                                'Penegak Bantara' => 'Penegak Bantara',
                                                'Penegak Laksana' => 'Penegak Laksana',
                                            ])
                                            ->required(),
                                    ]),
                            ]),
                        Tab::make('Akun')
                            ->schema([
                                Section::make('Ubah Password')
                                    ->schema([
                                        $this->getPasswordFormComponent(),
                                        $this->getPasswordConfirmationFormComponent(),
                                        $this->getCurrentPasswordFormComponent(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return [
            'data' => $data,
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['data'])) {
            $data = $data['data'];
        }

        if (! empty($data['same_as_domisili'])) {
            foreach ([
                'country',
                'province',
                'city',
                'district',
                'village',
                'rt',
                'rw',
                'postal_code',
                'street',
            ] as $attribute) {
                $data['asal_'.$attribute] = $data['domisili_'.$attribute] ?? $data['asal_'.$attribute] ?? null;
            }
        }

        unset($data['same_as_domisili']);

        return $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return url('/dashboard');
    }
}
