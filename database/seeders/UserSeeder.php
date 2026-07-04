<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Enums\UserKind;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@pramuka-usu.local'],
            [
                'name' => 'Admin Pramuka USU',
                'password' => Hash::make('password'),
                'jenis_user' => null,
                'is_active' => true,
            ],
        );

        $admin->syncRoles([RoleName::Admin->value]);

        $pramukaAdmin = User::query()->firstOrCreate(
            ['email' => 'pramuka@usu.ac.id'],
            [
                'name' => 'Admin Pramuka USU',
                'password' => Hash::make('webpramukausu1'),
                'jenis_user' => null,
                'is_active' => true,
            ],
        );

        $pramukaAdmin->syncRoles([RoleName::Admin->value]);

        $pembina = User::query()->firstOrCreate(
            ['email' => 'pembina@pramuka-usu.local'],
            [
                'name' => 'Pembina Pramuka',
                'password' => Hash::make('password'),
                'jenis_user' => UserKind::Pembina->value,
                'is_active' => true,
            ],
        );

        $pembina->syncRoles([RoleName::User->value]);

        $pesertaDidik = User::query()->firstOrCreate(
            ['email' => 'peserta@pramuka-usu.local'],
            [
                'name' => 'Peserta Didik Pramuka',
                'password' => Hash::make('password'),
                'jenis_user' => UserKind::PesertaDidik->value,
                'is_active' => true,
            ],
        );

        $pesertaDidik->syncRoles([RoleName::User->value]);
    }
}