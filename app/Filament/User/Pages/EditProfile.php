<?php

namespace App\Filament\User\Pages;

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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Filament\Notifications\Notification;

class EditProfile extends BaseEditProfile
{
    protected static ?string $title = 'Profil';

    protected string $view = 'filament.user.pages.edit-profile';

    public string $activeProfileTab = 'biodata';

    private const PROFILE_TAB_SECTIONS = [
        'biodata' => 'Biodata',
        'alamat' => 'Alamat',
        'riwayat-pendidikan' => 'Riwayat Pendidikan',
        'orang-tua' => 'Orang Tua',
        'data-pramuka' => 'Data Pramuka',
        'akun' => 'Akun',
    ];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('profile_tabs')
                    ->livewireProperty('activeProfileTab')
                    ->persistTabInQueryString(null)
                    ->tabs([
                        'biodata' => Tab::make('Biodata')
                            ->schema([
                                Section::make('Data Diri')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('data.name')->label('Nama')->disabled()->maxLength(255),
                                                TextInput::make('data.email')->label('Email')->email()->disabled()->maxLength(255),
                                                TextInput::make('data.birth_place')->label('Tempat Lahir')->maxLength(255),
                                                DatePicker::make('data.birth_date')->label('Tanggal Lahir'),
                                                Select::make('data.gender')->label('Jenis Kelamin')->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']),
                                                Select::make('data.religion')->label('Agama')->options(['Islam' => 'Islam','Kristen' => 'Kristen','Katolik' => 'Katolik','Hindu' => 'Hindu','Buddha' => 'Buddha','Konghucu' => 'Konghucu','Lainnya' => 'Lainnya']),
                                                Select::make('data.blood_type')->label('Golongan Darah')->options(['A'=>'A','B'=>'B','AB'=>'AB','O'=>'O','-'=>'Tidak Tahu']),
                                                TextInput::make('data.hobby')->label('Hobi')->maxLength(255),
                                                TextInput::make('data.siblings_count')->label('Jumlah Saudara')->numeric(),
                                                TextInput::make('data.whatsapp_number')->label('Nomor WhatsApp')->tel()->maxLength(30),
                                                Select::make('data.marital_status')->label('Status Perkawinan')->options(['Belum Kawin'=>'Belum Kawin','Kawin'=>'Kawin','Duda'=>'Duda','Janda'=>'Janda']),
                                                TextInput::make('data.job')->label('Pekerjaan')->maxLength(255),
                                                FileUpload::make('data.avatar_path')->label('Upload Pas Foto')->image()->directory('avatars')->disk('public')->visibility('public')->maxSize(2048)->imageCropAspectRatio('3:4'),
                                            ]),
                                    ]),
                            ]),
                        'alamat' => Tab::make('Alamat')
                            ->schema([
                                Section::make('Domisili')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('data.domisili_country')->label('Negara')->maxLength(255),
                                            TextInput::make('data.domisili_province')->label('Provinsi')->maxLength(255),
                                            TextInput::make('data.domisili_city')->label('Kota/Kabupaten')->maxLength(255),
                                            TextInput::make('data.domisili_district')->label('Kecamatan')->maxLength(255),
                                            TextInput::make('data.domisili_village')->label('Kelurahan/Desa')->maxLength(255),
                                            TextInput::make('data.domisili_rt')->label('RT')->maxLength(10),
                                            TextInput::make('data.domisili_rw')->label('RW')->maxLength(10),
                                            TextInput::make('data.domisili_postal_code')->label('Kode Pos')->maxLength(10),
                                        ]),
                                        TextInput::make('data.domisili_street')->label('Jalan')->maxLength(255),
                                    ]),
                                Section::make('Asal')
                                    ->schema([
                                        Checkbox::make('data.same_as_domisili')
                                            ->label('Samakan dengan Domisili')
                                            ->reactive()
                                            ->afterStateUpdated(function (callable $set, $state) {
                                                if ($state) {
                                                    foreach (['country','province','city','district','village','rt','rw','postal_code','street'] as $attr) {
                                                        $set('data.asal_'.$attr, data_get($this->data, 'data.domisili_'.$attr));
                                                    }
                                                }
                                            }),
                                        Grid::make(3)->schema([
                                            TextInput::make('data.asal_country')->label('Negara')->maxLength(255),
                                            TextInput::make('data.asal_province')->label('Provinsi')->maxLength(255),
                                            TextInput::make('data.asal_city')->label('Kota/Kabupaten')->maxLength(255),
                                            TextInput::make('data.asal_district')->label('Kecamatan')->maxLength(255),
                                            TextInput::make('data.asal_village')->label('Kelurahan/Desa')->maxLength(255),
                                            TextInput::make('data.asal_rt')->label('RT')->maxLength(10),
                                            TextInput::make('data.asal_rw')->label('RW')->maxLength(10),
                                            TextInput::make('data.asal_postal_code')->label('Kode Pos')->maxLength(10),
                                        ]),
                                        TextInput::make('data.asal_street')->label('Jalan')->maxLength(255),
                                    ]),
                            ]),
                        'riwayat-pendidikan' => Tab::make('Riwayat Pendidikan')
                            ->schema([
                                Section::make('Pendidikan')
                                    ->schema([
                                        Select::make('data.education_status')->label('Status')->options(['Mahasiswa'=>'Mahasiswa','Alumni'=>'Alumni','Bukan Mahasiswa'=>'Bukan Mahasiswa'])->reactive(),
                                        TextInput::make('data.nim')->label('NIM')->maxLength(100),
                                        TextInput::make('data.kampus')->label('Kampus')->maxLength(255),
                                        TextInput::make('data.fakultas')->label('Fakultas')->maxLength(255),
                                        TextInput::make('data.program_studi')->label('Program Studi')->maxLength(255),
                                    ]),
                            ]),
                        'orang-tua' => Tab::make('Orang Tua')
                            ->schema([
                                Section::make('Ayah')->schema([
                                    TextInput::make('data.father_name')->label('Nama')->maxLength(255),
                                    Select::make('data.father_status')->label('Status')->options(['Hidup'=>'Hidup','Meninggal'=>'Meninggal']),
                                    Textarea::make('data.father_address')->label('Alamat Lengkap')->rows(3),
                                    TextInput::make('data.father_phone')->label('Nomor Telepon')->tel()->maxLength(30),
                                ]),
                                Section::make('Ibu')->schema([
                                    TextInput::make('data.mother_name')->label('Nama')->maxLength(255),
                                    Select::make('data.mother_status')->label('Status')->options(['Hidup'=>'Hidup','Meninggal'=>'Meninggal']),
                                    Textarea::make('data.mother_address')->label('Alamat Lengkap')->rows(3),
                                    TextInput::make('data.mother_phone')->label('Nomor Telepon')->tel()->maxLength(30),
                                ]),
                                Section::make('Wali')->schema([
                                    TextInput::make('data.guardian_name')->label('Nama')->maxLength(255),
                                    Select::make('data.guardian_status')->label('Status')->options(['Hidup'=>'Hidup','Meninggal'=>'Meninggal']),
                                    Textarea::make('data.guardian_address')->label('Alamat Lengkap')->rows(3),
                                    TextInput::make('data.guardian_phone')->label('Nomor Telepon')->tel()->maxLength(30),
                                ]),
                            ]),
                        'data-pramuka' => Tab::make('Data Pramuka')
                            ->schema([
                                Section::make('Pramuka')->schema([
                                    Select::make('data.satuan')->label('Satuan')->options([
                                        'Majelis Pembimbing Gugus Depan Gerakan Pramuka Kota Medan 08-137 dan 08-138' => 'Majelis Pembimbing Gugus Depan Gerakan Pramuka Kota Medan 08-137 dan 08-138',
                                        'Gugus Depan Gerakan Pramuka Kota Medan 08-137' => 'Gugus Depan Gerakan Pramuka Kota Medan 08-137',
                                        'Gugus Depan Gerakan Pramuka Kota Medan 08-138' => 'Gugus Depan Gerakan Pramuka Kota Medan 08-138',
                                        'Racana Soetan Koemala Pontas' => 'Racana Soetan Koemala Pontas',
                                        'Racana Rasuna Said' => 'Racana Rasuna Said',
                                        'Ambalan Soetan Koemala Pontas' => 'Ambalan Soetan Koemala Pontas',
                                        'Ambalan Rasuna Said' => 'Ambalan Rasuna Said',
                                    ]),
                                    TextInput::make('data.jabatan')->label('Jabatan')->maxLength(255),
                                    TextInput::make('data.nta')->label('NTA')->maxLength(50),
                                    TextInput::make('data.tahun_masuk_pramuka_usu')->label('Tahun Masuk Pramuka USU')->numeric(),
                                    TextInput::make('data.nama_omantaru')->label('Nama OMANTARU')->maxLength(255),
                                    Select::make('data.golongan')->label('Golongan')->options(['Pembina'=>'Pembina','Pandega'=>'Pandega','Penegak'=>'Penegak']),
                                    Select::make('data.tingkatan')->label('Tingkatan')->options(['KPD'=>'KPD','KPL'=>'KPL','KMD'=>'KMD','KML'=>'KML','Calon Pandega'=>'Calon Pandega','Pandega'=>'Pandega','Calon Penegak'=>'Calon Penegak','Penegak Bantara'=>'Penegak Bantara','Penegak Laksana'=>'Penegak Laksana']),
                                ]),
                            ]),
                        'akun' => Tab::make('Akun')
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

    /**
     * Save only the fields from the active tab section.
     * Called from Alpine.js via $wire.saveSection(tabName).
     */
    public function saveSection(?string $tab = null): void
    {
        $tab = Str::slug($tab ?? $this->activeProfileTab);
        $section = self::PROFILE_TAB_SECTIONS[$tab] ?? null;

        if ($section === null) {
            Notification::make()->title('Bagian profil tidak dikenali')->danger()->send();
            return;
        }

        $user = Auth::user();

        // ---- AKUN (password only) ----
        if ($section === 'Akun') {
            $password = data_get($this->data, 'password');
            $passwordConfirmation = data_get($this->data, 'passwordConfirmation');
            $currentPassword = data_get($this->data, 'currentPassword');

            if (! filled($password) && ! filled($passwordConfirmation) && ! filled($currentPassword)) {
                Notification::make()->title('Tidak ada perubahan kata sandi')->warning()->send();
                return;
            }

            $v = Validator::make(
                [
                    'password' => $password,
                    'password_confirmation' => $passwordConfirmation,
                    'current_password' => $currentPassword,
                ],
                [
                    'password' => ['required', 'string', Password::default(), 'same:password_confirmation'],
                    'password_confirmation' => ['required'],
                    'current_password' => ['required'],
                ],
                [
                    'password.required' => 'Kata sandi baru harus diisi',
                    'password_confirmation.required' => 'Konfirmasi kata sandi harus diisi',
                    'current_password.required' => 'Kata sandi saat ini harus diisi',
                    'password.same' => 'Konfirmasi kata sandi tidak cocok',
                ]
            );

            if ($v->fails()) {
                Notification::make()->title($v->errors()->first())->danger()->send();
                return;
            }

            if (! Hash::check($currentPassword, $user->getAuthPassword())) {
                Notification::make()->title('Kata sandi saat ini salah')->danger()->send();
                return;
            }

            $user->password = Hash::make($password);
            $user->save();

            unset($this->data['password'], $this->data['passwordConfirmation'], $this->data['currentPassword']);
            Notification::make()->title('Kata sandi berhasil diperbarui')->success()->send();
            return;
        }

        // ---- OTHER SECTIONS ----
        $map = [
            'Biodata' => ['name','email','birth_place','birth_date','gender','religion','blood_type','hobby','siblings_count','whatsapp_number','marital_status','job','avatar_path'],
            'Alamat' => ['domisili_country','domisili_province','domisili_city','domisili_district','domisili_village','domisili_rt','domisili_rw','domisili_postal_code','domisili_street','asal_country','asal_province','asal_city','asal_district','asal_village','asal_rt','asal_rw','asal_postal_code','asal_street','same_as_domisili'],
            'Riwayat Pendidikan' => ['education_status','nim','kampus','fakultas','program_studi'],
            'Orang Tua' => ['father_name','father_status','father_address','father_phone','mother_name','mother_status','mother_address','mother_phone','guardian_name','guardian_status','guardian_address','guardian_phone'],
            'Data Pramuka' => ['satuan','jabatan','nta','tahun_masuk_pramuka_usu','nama_omantaru','golongan','tingkatan'],
        ];

        if (! isset($map[$section])) {
            Notification::make()->title('Bagian tidak dikenali')->danger()->send();
            return;
        }

        $allData = data_get($this->data, 'data', []);
        $allowed = $map[$section];
        $sectionData = array_intersect_key($allData, array_flip($allowed));

        if (empty($sectionData)) {
            Notification::make()->title('Tidak ada data untuk disimpan')->warning()->send();
            return;
        }

        // Per-section validation rules (only required fields for this section)
        $sectionRules = $this->getSectionRules($section, $allData);

        $v = Validator::make($sectionData, $sectionRules);
        if ($v->fails()) {
            Notification::make()->title($v->errors()->first())->danger()->send();
            return;
        }

        // same_as_domisili logic for Alamat section
        if ($section === 'Alamat' && ! empty($sectionData['same_as_domisili'])) {
            foreach (['country','province','city','district','village','rt','rw','postal_code','street'] as $attr) {
                $sectionData['asal_'.$attr] = $allData['domisili_'.$attr] ?? $sectionData['asal_'.$attr] ?? null;
            }
        }
        unset($sectionData['same_as_domisili']);

        $user->fill($sectionData);
        $user->save();

        Notification::make()->title('Berhasil disimpan')->success()->send();
    }

    /**
     * Get per-section validation rules by field name.
     */
    private function getSectionRules(string $section, array $allData): array
    {
        $rules = [];

        switch ($section) {
            case 'Biodata':
                $rules = [
                    'birth_place' => ['required', 'string', 'max:255'],
                    'birth_date' => ['required', 'date'],
                    'gender' => ['required', 'string'],
                    'religion' => ['required', 'string'],
                    'blood_type' => ['required', 'string'],
                    'hobby' => ['required', 'string', 'max:255'],
                    'siblings_count' => ['required', 'numeric'],
                    'whatsapp_number' => ['required', 'string', 'max:30'],
                    'marital_status' => ['required', 'string'],
                    'job' => ['required', 'string', 'max:255'],
                    'avatar_path' => ['nullable'],
                ];
                break;

            case 'Alamat':
                $rules = [
                    'domisili_country' => ['required', 'string', 'max:255'],
                    'domisili_province' => ['required', 'string', 'max:255'],
                    'domisili_city' => ['required', 'string', 'max:255'],
                    'domisili_district' => ['required', 'string', 'max:255'],
                    'domisili_village' => ['required', 'string', 'max:255'],
                    'domisili_rt' => ['required', 'string', 'max:10'],
                    'domisili_rw' => ['required', 'string', 'max:10'],
                    'domisili_postal_code' => ['required', 'string', 'max:10'],
                    'domisili_street' => ['required', 'string', 'max:255'],
                    'asal_country' => ['nullable', 'string', 'max:255'],
                    'asal_province' => ['nullable', 'string', 'max:255'],
                    'asal_city' => ['nullable', 'string', 'max:255'],
                    'asal_district' => ['nullable', 'string', 'max:255'],
                    'asal_village' => ['nullable', 'string', 'max:255'],
                    'asal_rt' => ['nullable', 'string', 'max:10'],
                    'asal_rw' => ['nullable', 'string', 'max:10'],
                    'asal_postal_code' => ['nullable', 'string', 'max:10'],
                    'asal_street' => ['nullable', 'string', 'max:255'],
                ];
                break;

            case 'Riwayat Pendidikan':
                $rules = [
                    'education_status' => ['required', 'string'],
                    'nim' => ['nullable', 'string', 'max:100'],
                    'kampus' => ['nullable', 'string', 'max:255'],
                    'fakultas' => ['nullable', 'string', 'max:255'],
                    'program_studi' => ['nullable', 'string', 'max:255'],
                ];
                // Conditional: NIM required if Mahasiswa/Alumni
                $eduStatus = data_get($allData, 'education_status');
                if (in_array($eduStatus, ['Mahasiswa', 'Alumni'])) {
                    $rules['nim'] = ['required', 'string', 'max:100'];
                    $rules['kampus'] = ['required', 'string', 'max:255'];
                    $rules['fakultas'] = ['required', 'string', 'max:255'];
                    $rules['program_studi'] = ['required', 'string', 'max:255'];
                }
                break;

            case 'Orang Tua':
                $rules = [
                    'father_name' => ['required', 'string', 'max:255'],
                    'father_status' => ['required', 'string'],
                    'father_address' => ['required', 'string'],
                    'father_phone' => ['required', 'string', 'max:30'],
                    'mother_name' => ['required', 'string', 'max:255'],
                    'mother_status' => ['required', 'string'],
                    'mother_address' => ['required', 'string'],
                    'mother_phone' => ['required', 'string', 'max:30'],
                    'guardian_name' => ['nullable', 'string', 'max:255'],
                    'guardian_status' => ['nullable', 'string'],
                    'guardian_address' => ['nullable', 'string'],
                    'guardian_phone' => ['nullable', 'string', 'max:30'],
                ];
                break;

            case 'Data Pramuka':
                $rules = [
                    'satuan' => ['required', 'string'],
                    'jabatan' => ['required', 'string', 'max:255'],
                    'nta' => ['required', 'string', 'max:50'],
                    'tahun_masuk_pramuka_usu' => ['required', 'numeric'],
                    'nama_omantaru' => ['required', 'string', 'max:255'],
                    'golongan' => ['required', 'string'],
                    'tingkatan' => ['required', 'string'],
                ];
                break;
        }

        return $rules;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return ['data' => $data];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['data'])) {
            $data = $data['data'];
        }
        if (! empty($data['same_as_domisili'])) {
            foreach (['country','province','city','district','village','rt','rw','postal_code','street'] as $attribute) {
                $data['asal_'.$attribute] = $data['domisili_'.$attribute] ?? $data['asal_'.$attribute] ?? null;
            }
        }
        unset($data['same_as_domisili']);
        return $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return url('/user');
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
