<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        Role::create(['name' => 'Guru', 'slug' => 'guru']);
        Role::create(['name' => 'Siswa', 'slug' => 'siswa']);

        // Buat user admin default
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@unsri.ac.id',
            'password' => bcrypt('password'), // Ganti dengan password aman
            // 'two_factor_secret' => null,
            // 'two_factor_recovery_codes' => null,
            // 'two_factor_confirmed_at' => null,


        ]);

        // Berikan role admin ke user tersebut
        $adminUser->assignRole('admin');

        // buat akun guru
        $guruUser = User::factory()->create([
            'name' => 'Guru',
            'username' => 'guru',
            'email' => 'guru@gmail.com',
            'password' => bcrypt('password'), // Ganti dengan password aman
            // 'two_factor_secret' => null,
            // 'two_factor_recovery_codes' => null,
            // 'two_factor_confirmed_at' => null,
        ]);

        // Berikan role guru ke user tersebut
        $guruUser->assignRole('guru');
    }
}
