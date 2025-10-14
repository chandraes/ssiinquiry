<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('get_setting')) {
    /**
     * Mengambil nilai pengaturan dari database atau cache.
     */
    function get_setting(string $key, $default = null): ?string
    {
        // Coba ambil dari cache terlebih dahulu
        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
}

// Tambahkan helper lain jika perlu
