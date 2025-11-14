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
        User::create([
            'name' => 'Fest Admin',
            'email' => 'admin@talentsmeet.com',
            'password' => Hash::make('admin123'),  // ✅ Admin password
            'role' => 'admin',
        ]);

        // 🔹 Judges
        User::create([
            'name' => 'Judge One',
            'email' => 'judge@talentsmeet.com',
            'password' => Hash::make('judge123'),  // ✅ Judge password
            'role' => 'judge',
        ]);

        // 🔹 Stage Admin
        User::create([
            'name' => 'Stage Admin',
            'email' => 'stageadmin@talentsmeet.com',
            'password' => Hash::make('stage123'),  // ✅ Stage Admin password
            'role' => 'stage_admin',
        ]);

        // 🔹 Institutions with Streams
        $institutions = [
            [
                'name' => 'Darul Huda Sharia College',
                'email' => 'sharia@college.com',
                'stream' => 'sharia',
                'password' => 'sharia123',
            ],
            [
                'name' => 'Darul Huda SHE College',
                'email' => 'she@college.com',
                'stream' => 'she',
                'password' => 'she123',
            ],
            [
                'name' => 'Darul Huda Life College',
                'email' => 'life@college.com',
                'stream' => 'life',
                'password' => 'life123',
            ],
            [
                'name' => 'Darul Huda Bayyinath College',
                'email' => 'bayyinath@college.com',
                'stream' => 'bayyinath',
                'password' => 'bayyinath123',
            ],
        ];

        foreach ($institutions as $data) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),  // ✅ Password hashed properly
                'role' => 'institution',
                'stream' => $data['stream'],
            ]);
        }
    }
}
