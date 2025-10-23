<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserMuridSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 10 user murid
        $data = [
            [
                'name' => 'Murid Satu',
                'username' => 'murid1',
                'email' => 'murid1@gmail.com',
                'password' => bcrypt('password'), // Ganti dengan password aman
            ],
            [
                'name' => 'Murid Dua',
                'username' => 'murid2',
                'email' => 'lmurid2@gmail.com',
                'password' => bcrypt('password'), // Ganti dengan password aman
            ],
            [
                'name' => 'Murid Tiga',
                'username' => 'murid3',
                'email' => 'murid3@gmail.com',
                'password' => bcrypt('password'), // Ganti dengan password aman
            ],
            [
                'name' => 'Murid Empat',
                'username' => 'murid4',
                'email' => 'murid4@gmail.com',
                'password' => bcrypt('password'), // Ganti dengan password aman
            ],
        ];

        foreach ($data as $userData) {
            $user = \App\Models\User::factory()->create($userData);
            $user->assignRole('siswa');
        }
    }
}
