<?php

namespace App\Http\Controllers;

use App\Models\Phyphox;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhyphoxController extends Controller
{
    public function index()
    {
        $phyphoxes = Phyphox::all();
        return view('phyphox.index', compact('phyphoxes'));
    }

    public function create()
    {
        return view('phyphox.create');
    }

    
    public function store(Request $request)
    {
        // 1. Validasi Data Input
        $request->validate([
            'kategori' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'is_active' => 'in:0,1',
            // Validasi file 'icon'
            'icon' => 'required|image|mimes:jpg,jpeg,png,svg|max:2048', 
        ]);

        // 2. Persiapan Data
        $data = $request->except('icon');
        
        // 3. Proses Upload File 'icon'
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $extension = $file->getClientOriginalExtension();

            // Membuat nama file yang unik dan berbasis slug dari 'nama' phyphox
            // Contoh: my-modul_20251022_093000.png
            $fileName = Str::slug($request->nama) . '_' . date('Ymd_His') . '.' . $extension;

            // Simpan ke folder 'icons' di disk 'public'
            // Jika Anda ingin menggunakan folder 'phyphox_icons', ubah 'icons'
            $path = $file->storeAs('phyphox_icons', $fileName, 'public'); 

            // Simpan path file ke dalam array data
            $data['icon'] = $path;
        }

        // 4. Simpan Data ke Database
        try {
            Phyphox::create($data);

            return redirect()->route('phyphox.index')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            // Hapus file yang sudah terlanjur di-upload jika terjadi error database
            if (isset($data['icon']) && Storage::disk('public')->exists($data['icon'])) {
                Storage::disk('public')->delete($data['icon']);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function destroy(Phyphox $phyphox)
    {
        $phyphox->delete();

        return redirect()->route('phyphox.index')->with('success', 'Data berhasil dihapus.');
    }
}
