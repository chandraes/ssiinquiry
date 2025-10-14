<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        // Tampilkan halaman pengaturan
        // Data diambil melalui helper function get_setting() di view
        return view('admin.settings.index');
    }

   public function update(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'app_name'      => 'required|string|max:255',
            // File: Hanya boleh gambar, ekstensi tertentu, dan batasan ukuran
            'app_logo'      => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // 2048 KB = 2MB
            'app_favicon'   => 'nullable|image|mimes:ico,png,jpg|max:50',      // 50 KB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. Proses Data Teks
        $this->saveSetting('app_name', $request->app_name);

        // 3. Proses Upload File (Logo & Favicon)
        // Logika handleFileUpload di bawah akan otomatis mengganti file lama
        $this->handleFileUpload($request, 'app_logo');
        $this->handleFileUpload($request, 'app_favicon');

        // 4. Clear Cache Pengaturan (Penting!)
        // Hapus cache agar helper function get_setting() mengambil nilai baru
        Cache::forget('settings.app_name');
        Cache::forget('settings.app_logo');
        Cache::forget('settings.app_favicon');

        return back()->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }

    // Pastikan Anda memiliki kedua helper function ini di SettingController
    protected function saveSetting(string $key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    protected function handleFileUpload(Request $request, string $fileKey)
    {
        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);
            $oldPath = Setting::where('key', $fileKey)->value('value');

            // Hapus file lama jika ada dan file baru berhasil diupload
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            // Simpan file baru di disk 'public' di folder 'settings'
            // Laravel akan otomatis memberi nama unik (HashName)
            $path = $file->store('settings', 'public');

            // Simpan path relatif ke database
            $this->saveSetting($fileKey, $path);

        } elseif ($request->input("{$fileKey}_remove") == 1) {
            // Logika opsional jika Anda ingin mengizinkan penghapusan file (Dropify biasanya memiliki fitur ini)
            $oldPath = Setting::where('key', $fileKey)->value('value');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            // Hapus path dari database
            $this->saveSetting($fileKey, null);
        }
    }
}
