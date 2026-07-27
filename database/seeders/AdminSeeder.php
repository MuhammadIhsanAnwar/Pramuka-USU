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
    }
}