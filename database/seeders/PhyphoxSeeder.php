<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Phyphox;

class PhyphoxSeeder extends Seeder
{
    public function run(): void
    {
        Phyphox::insert([
            [
                'kategori' => 'Raw Sensors',
                'nama' => 'Light',
                'is_active' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Raw Sensors',
                'nama' => 'Location (GPS)',
                'is_active' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Acoustics',
                'nama' => 'Audio Amplitude',
                'is_active' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Acoustics',
                'nama' => 'Audio Spectrum',
                'is_active' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
