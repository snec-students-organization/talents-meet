<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Admin
        // 🔹 Admin
        User::firstOrCreate(
            ['email' => 'admin@talentsmeet.com'],
            [
                'name' => 'Fest Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 🔹 Judges
        User::firstOrCreate(
            ['email' => 'judge@talentsmeet.com'],
            [
                'name' => 'Judge One',
                'password' => Hash::make('judge123'),
                'role' => 'judge',
            ]
        );

        // 🔹 Stage Admin
        User::firstOrCreate(
            ['email' => 'stageadmin@talentsmeet.com'],
            [
                'name' => 'Stage Admin',
                'password' => Hash::make('stage123'),
                'role' => 'stage_admin',
            ]
        );

        // 🔹 Institutions with Streams & Levels
        $institutions = [
            [
                'name' => 'Darul Huda Sharia College',
                'email' => 'sharia@college.com',
                'stream' => 'sharia',
                'password' => 'sharia123',
                'levels' => ['Sanaviyya Ulya', 'Bakalooriyya', 'Majestar'], // Full access
            ],
            [
                'name' => 'shamsul ulama Sharia College',
                'email' => 'sharddia@college.com',
                'stream' => 'sharia',
                'password' => 'sharia123',
                'levels' => ['Sanaviyya Ulya'], // Only Sanaviyya
            ],
            [
                'name' => 'liwa ul huda Sharia College',
                'email' => 'shardzddzia@college.com',
                'stream' => 'sharia',
                'password' => 'sharia123',
                'levels' => ['Bakalooriyya', 'Majestar'], // Higher levels only
            ],
             [
                'name' => 'kmmec Sharia College',
                'email' => 'shardzddppppzia@college.com',
                'stream' => 'sharia',
                'password' => 'sharia123',
                'levels' => ['Sanaviyya Ulya', 'Majestar'],
            ],
            [
                'name' => 'manar SHE College',
                'email' => 'she@college.com',
                'stream' => 'she',
                'password' => 'she123',
                'levels' => ['Sanaviyya Ulya'], 
            ],
            [
                'name' => 'Darul Huda Life College',
                'email' => 'life@college.com',
                'stream' => 'life',
                'password' => 'life123',
                'levels' => null, // Expecting logic to handle null as all? or strict?
            ],
            [
                'name' => 'Darul Huda Bayyinath College',
                'email' => 'bayyinath@college.com',
                'stream' => 'bayyinath',
                'password' => 'bayyinath123',
                'levels' => null,
            ],
        ];

        foreach ($institutions as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => 'institution',
                    'stream' => $data['stream'],
                    'levels' => $data['levels'] ?? null,
                ]
            );
        }
    }
}
