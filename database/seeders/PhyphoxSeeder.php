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
                'icon' => 'light_icon.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Raw Sensors',
                'nama' => 'Gyroscope',
                'is_active' => '0',
                'icon' => 'gyroscope_icon.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Acoustics',
                'nama' => 'Audio Amplitude',
                'is_active' => '1',
                'icon' => 'audio_amplitude_icon.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Acoustics',
                'nama' => 'Audio Spectrum',
                'is_active' => '1',
                'icon' => 'audio_spectrum_icon.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
