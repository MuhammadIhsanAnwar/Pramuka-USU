<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TamuUsersSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = [
            ['email' => 'ariwijayantii24@gmail.com', 'name' => 'Asri Wijayanti'],
            ['email' => 'auliasaski396@gmail.com', 'name' => 'Saski Aulia'],
            ['email' => 'hodmaidaharahap8271@gmail.com', 'name' => 'Siti Hotmaida Harahap'],
            ['email' => 'ayasalfaiq588@gmail.com', 'name' => 'Abdullah Farras Al Faiq'],
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'password' => Hash::make('tamusiprausu1'),
                    'jenis_user' => 'tamu',
                    'is_active' => true,
                ]
            );

            if (method_exists($user, 'assignRole')) {
                $user->assignRole(RoleName::User->value);
            }
        }
    }
}
