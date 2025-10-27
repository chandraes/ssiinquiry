<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Modul;
use App\Models\Phyphox;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * [DIPERBAIKI & DIOPTIMALKAN]
     * Mengatasi N+1 Query dari 'relatedPhyphox' dengan manual eager-loading.
     */
    public function index()
    {
        $userLogin = Auth::user();
        $data = ['userLogin' => $userLogin];

        // [PENTING] Gunakan 'slug' (cth: 'siswa') bukan 'nama' (cth: 'Siswa')
        // Sesuaikan 'siswa' dengan 'slug' di tabel roles Anda
        if ($userLogin->hasRole('siswa')) {
            // ==================
            // DATA UNTUK SISWA
            // ==================

            // 1. Ambil ID kelas siswa
            $myClassIds = $userLogin->kelas->pluck('id');

            // 2. Ambil data Kelas Saya (DI SINILAH $myClasses DIBUAT)
            $data['myClasses'] = Kelas::whereIn('id', $myClassIds)
                                    ->with('modul') // Eager load modul
                                    ->get();

            // 3. Ambil ID modul yang sudah diikuti siswa
            $myModuleIds = $data['myClasses']->pluck('modul.id');

            // 4. Ambil modul lain (DI SINILAH $allOtherModules DIBUAT)
            $data['allOtherModules'] = Modul::whereNotIn('id', $myModuleIds)
                                         ->get();

        } else {
            // ==================
            // DATA UNTUK ADMIN & GURU
            // ==================

            // 1. [OPTIMASI] Ambil modul DAN relasi kelas (dengan jumlah peserta)
            $moduls = Modul::with([
                'kelas' => function ($query) {
                    $query->withCount('peserta');
                }
            ])
            ->latest()
            ->get();

            // 2. [PERBAIKAN N+1] Ambil semua ID phyphox dari modul
            $allPhyphoxIds = $moduls->pluck('phyphox_id')
                                   ->flatten()
                                   ->filter()
                                   ->unique();

            // 3. [PERBAIKAN N+1] Jalankan SATU kueri untuk semua alat Phyphox
            $data['allPhyphoxTools'] = Phyphox::whereIn('id', $allPhyphoxIds)
                                            ->get()
                                            ->keyBy('id');

            // 4. Kirim data modul (DI SINILAH $modul DIBUAT)
            $data['modul'] = $moduls;

            // 5. Data untuk modal 'Tambah Modul'
            $data['phyphox'] = Phyphox::where('is_active', '1')->get();
        }

        // 6. Kirim semua data ke view
        return view('home', $data);
    }
}
