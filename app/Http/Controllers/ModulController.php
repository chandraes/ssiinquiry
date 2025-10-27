<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Modul;
use App\Models\Phyphox;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ModulController extends Controller
{
    public function index()
    {
        $userLogin = Auth::user();

        // Mulai query
        $query = Modul::query();

        // 1. [OPTIMASI] Filter berdasarkan Role
        // if ($userLogin->roles->contains('name', 'Guru') && !$userLogin->roles->contains('name', 'Administrator')) {
        //     // Jika HANYA guru, tampilkan modul yang terhubung dengannya
        //     // (Menggunakan relasi 'moduls' dari model User)
        //     $query->whereHas('users', function ($q) use ($userLogin) {
        //         $q->where('user_id', $userLogin->id);
        //     });
        // }

        // 2. [OPTIMASI] Eager Load relasi & Hitung jumlah kelas
        // Asumsi Model 'Modul' Anda memiliki relasi 'hasMany(Kelas::class)'
        $moduls = $query->withCount('kelas') // Membuat properti 'kelas_count'
                       ->latest()
                       ->get();

        // Variabel ini dibutuhkan oleh modal 'modul.create' (Sudah benar)
        $phyphox = Phyphox::where('is_active', '1')->get();

        return view('modul.index', compact('moduls', 'userLogin', 'phyphox'));
    }

    public function showJson(Modul $modul)
    {
        return response()->json([
            'id' => $modul->id,
            'judul' => $modul->getTranslations('judul'), // Kirim {en, id}
            'deskripsi' => $modul->getTranslations('deskripsi'), // Kirim {en, id}
            // Tambahkan field lain yang Anda perlukan di modal edit
        ]);
    }

    /**
     * [DIUBAH] Menyimpan modul baru, sekarang dengan upload gambar
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul.id' => 'required|string|max:255',
            'judul.en' => 'required|string|max:255',
            'deskripsi.id' => 'nullable|string',
            'deskripsi.en' => 'nullable|string',
            'phyphox_id' => 'required|array',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // <-- TAMBAHKAN VALIDASI GAMBAR
        ]);

        try {
            $path = null;
            if ($request->hasFile('image')) {
                // Simpan gambar ke 'storage/app/public/modul_images'
                $path = $request->file('image')->store('modul_images', 'public');
            }

            Modul::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'phyphox_id' => $request->phyphox_id,
                'image' => $path, // <-- SIMPAN PATH GAMBAR
            ]);

            return redirect()->back()->with('success', 'Data modul berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    public function search(Request $request)
    {
        $userLogin = auth()->user(); // ambil user login
        $search = $request->q;

        // Query dasar pencarian user
        $query = User::where('name', 'like', "%{$search}%")
                    ->select('id', 'name')
                    ->limit(10);

        // 🔒 Batasi hasil berdasarkan role user yang login
        if ($userLogin->roles->contains('name', 'Guru')) {
            // Guru hanya bisa melihat Guru
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'Guru');
            });
        } elseif ($userLogin->roles->contains('name', 'Administrator')) {
            // Admin bisa melihat Guru dan Admin
            $query->whereHas('roles', function ($q) {
                $q->whereIn('name', ['Guru', 'Administrator']);
            });
        } else {
            // Role lain tidak boleh melihat siapa pun
            $query->whereRaw('1 = 0'); // selalu false → hasil kosong
        }

        $users = $query->get();

        return response()->json($users);
    }

    public function search_phyphox(Request $request)
    {
        $userLogin = auth()->user(); // Ambil user yang sedang login
        $search = $request->q;

        // 1. Query dasar pencarian data Phyphox
        $query = Phyphox::where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
            ->orWhere('kategori', 'like', "%{$search}%"); // Sesuaikan kolom pencarian
        })
        ->select('id', 'nama', 'kategori') // Pilih kolom yang dibutuhkan
        ->limit(10);

        $phyphoxData = $query->get();

        // Opsional: Format output untuk kemudahan pada frontend (misalnya, Select2)
        $formattedData = $phyphoxData->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->kategori . ' (' . $item->nama . ')' // Sesuaikan format teks
            ];
        });

        return response()->json($formattedData);
    }


    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'judul.id' => 'required|string|max:255',
    //         'judul.en' => 'required|string|max:255',
    //         'deskripsi.id' => 'nullable|string',
    //         'deskripsi.en' => 'nullable|string',
    //         'phyphox_id' => 'required|array',
    //     ]);

    //     try {
    //         $modul = Modul::create([
    //             // [DIUBAH] Kita sekarang mengirim array 'judul' dan 'deskripsi'
    //             // Model akan otomatis menanganinya berkat trait HasTranslations
    //             'judul' => $request->judul,
    //             'deskripsi' => $request->deskripsi,
    //             'phyphox_id' => $request->phyphox_id,
    //         ]);

    //         // ... (logika attach owner/user Anda) ...

    //         return redirect()->back()->with('success', 'Data modul berhasil dibuat!');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_id'      => 'required|string|max:255',
            'judul_en'      => 'required|string|max:255',
            'deskripsi_id'  => 'nullable|string',
            'deskripsi_en'  => 'nullable|string',
            'owner'         => 'nullable|array',  // multiple owner
            'owner.*'       => 'exists:users,id',
        ]);

        try {
            $modul = Modul::with('users')->findOrFail($id);
            $userLogin = auth()->user();

            // Cek apakah user login adalah owner atau admin
            $isOwner = $modul->users->contains('id', $userLogin->id) || $userLogin->roles->contains('name', 'Administrator');

            if (!$isOwner) {
                return redirect()->back()->with('error', 'Kamu tidak memiliki izin untuk mengubah data modul ini.');
            }

            // Update data modul
            $modul->update([
                'judul_id'      => $request->judul_id,
                'judul_en'      => $request->judul_en,
                'deskripsi_id'  => $request->deskripsi_id,
                'deskripsi_en'  => $request->deskripsi_en,
            ]);

            // Sinkronisasi owner di tabel pivot (jika diubah)
            if ($request->has('owner')) {
                $modul->users()->sync($request->owner);
            }

            return redirect()->back()->with('success', 'Data modul berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $modul = Modul::with('users')->findOrFail($id);
            $userLogin = auth()->user();

            // Cek apakah user login adalah owner atau admin
            $isOwner = $modul->users->contains('id', $userLogin->id) || $userLogin->roles->contains('name', 'Administrator');

            if (!$isOwner) {
                return redirect()->back()->with('error', 'Kamu tidak memiliki izin untuk menghapus data modul ini.');
            }

            $modul->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Modul $modul)
    {
        // $modul sudah otomatis diambil oleh Laravel (route model binding)

        // Sekarang, kita muat relasi 'subModules'
        // Kita juga bisa mengurutkannya langsung di sini
        $modul->load(['subModules' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);

        // Kirim data modul (yang sekarang berisi sub-modul) ke view
        return view('modul.show', compact('modul'));
    }

    public function show_siswa(Modul $modul)
    {
        // $modul sudah otomatis diambil oleh Laravel (route model binding)

        // Sekarang, kita muat relasi 'subModules'
        // Kita juga bisa mengurutkannya langsung di sini
        $modul->load(['subModules' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);

        // Kirim data modul (yang sekarang berisi sub-modul) ke view
        return view('modul.show-siswa', compact('modul'));
    }


}
