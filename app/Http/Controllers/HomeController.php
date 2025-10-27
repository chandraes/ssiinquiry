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

        if ($userLogin->hasRole('Siswa')) {
            // ==================
            // DATA UNTUK SISWA (Tidak Berubah)
            // ==================
            $myClassIds = $userLogin->kelas->pluck('id');
            $data['myClasses'] = Kelas::whereIn('id', $myClassIds)
                                    ->with('modul')
                                    ->get();
            $myModuleIds = $data['myClasses']->pluck('modul.id');
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
            $allPhyphoxIds = $moduls->pluck('phyphox_id') // Ambil array [[1,2], [2,3]]
                                   ->flatten()          // Jadikan [1,2,2,3]
                                   ->filter()           // Hapus null
                                   ->unique();          // Jadikan [1,2,3]

            // 3. [PERBAIKAN N+1] Jalankan SATU kueri untuk semua alat Phyphox
            // Kita gunakan keyBy('id') agar pencarian di view cepat
            $data['allPhyphoxTools'] = Phyphox::whereIn('id', $allPhyphoxIds)
                                            ->get()
                                            ->keyBy('id');

            // 4. Kirim data modul
            $data['modul'] = $moduls;

            // 5. Data untuk modal 'Tambah Modul'
            $data['phyphox'] = Phyphox::where('is_active', '1')->get();
        }

        return view('home', $data);
    }
}
