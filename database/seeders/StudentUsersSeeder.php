<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Enums\UserKind;

class StudentUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = 'pesdiksiprausu1';

        $list = [
            'febymiftahul3@gmail.com' => 'Feby Miftahul Jannah',
            'samawati2022@gmail.com' => 'Samawati Tinambunan',
            'revalinamanika@gmail.com' => 'Revalina Riris Maynian Manik',
            'shentyamalau@gmail.com' => 'Senthya Malau',
            'azzuraelvina22@gmail.com' => 'Azzura Elvina Manurung',
            'salsaaulia769@gmail.com' => 'Salsa Aulia',
            'ikhsanmaulanaaa122@gmail.com' => 'Ikhsan Maulana',
            'dwisudarma68@gmail.com' => 'Dwi Sudarma',
            'muhammadihsananwar3@gmail.com' => 'Muhammad Ihsan Anwar',
            'marifh2602@gmail.com' => 'Muhammad Arif Habiburrahman',
            'yasinkuada0719@gmail.com' => 'Dzakwan Yasin Kakomole Kuada',
            'marrardika20@gmail.com' => 'Dhamar Ardika Kirana Syahputra',
            'giwensalim6@gmail.com' => 'Giwen Salim',
            'samueltampubolon390@gmail.com' => 'Samuel Tampubolon',
            'faizalrasyiq186@gmail.com' => 'Faizal Rasyig',
            'yazidsani24@gmail.com' => 'Yazid Anshori Sani',
            'najwanailah1603@gmail.com' => 'Najwa Nailah Mawaddah',
            'sharaswaty2003@gmail.com' => 'Sri Rachma Sharaswaty',
            'coraangelyna@gmail.com' => 'Cora Angelyna Hermanto',
            'mitajannah151003@gmail.com' => 'Miftahul Zannah, A.Md.M.',
            'yuliadlm0702@gmail.com' => 'Yulia Pratiwi, S.S.',
            'auliarmdni1237@gmail.com' => 'Suci Aulia Ramadani Fitri',
            'delvikasylvesterzebua@gmail.com' => 'Delvika Sylvester Zebua',
            'raziefmhrp@gmail.com' => 'Achmad Razief Musyaffa Harahap, S.S.',
            'fatan250217@gmail.com' => 'Alfathan Bagas Kurnia',
            'helmisinambela56@gmail.com' => 'Helmi Sinambela',
            'ronaldpinem2018@gmail.com' => 'Ronald Carda Pinem',
            'abdul.azis3838@gmail.com' => 'Abdul Azis',
            'akbarrsurya3@gmail.com' => 'Ade Surya Akbar',
            'asengarya1100@gmail.com' => 'Arbie Aryandy',
            'yassirliamri97@gmail.com' => 'Yassirli Amri',
        ];

        foreach ($list as $email => $name) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'jenis_user' => UserKind::PesertaDidik->value,
                    'password' => Hash::make($password),
                    'is_active' => true,
                ]
            );

            if (method_exists($user, 'assignRole')) {
                if (! $user->hasRole('User')) {
                    $user->assignRole('User');
                }
            }
        }
    }
}
