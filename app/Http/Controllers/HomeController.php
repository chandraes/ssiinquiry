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
     * Show the application dashboard based on user role.
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userLogin = Auth::user()->load('roles'); // Muat relasi roles
        $viewData = ['userLogin' => $userLogin]; // Data dasar untuk semua view

        // Cek Role (Asumsi Anda punya relasi 'roles' di model User)
        // Sesuaikan 'Siswa', 'Administrator', 'Guru' dengan nama role Anda
        if ($userLogin->roles->contains('name', 'Siswa')) {

            // ======================================
            // LOGIKA UNTUK SISWA
            // ======================================

            // 1. Ambil "Kelas Saya" (Gunakan relasi 'kelas' dari model User)
            // Kita juga eager load 'modul' agar efisien
            $myClasses = $userLogin->kelas()->with('modul')->latest()->get();
            $viewData['myClasses'] = $myClasses;

            // 2. Ambil "Modul Lain"
            $myEnrolledModulIds = $myClasses->pluck('modul.id')->unique();
            $allOtherModules = Modul::whereNotIn('id', $myEnrolledModulIds)->latest()->get();
            $viewData['allOtherModules'] = $allOtherModules;

        } elseif ($userLogin->roles->contains('name', 'Administrator') || $userLogin->roles->contains('name', 'Guru')) {

            // ======================================
            // LOGIKA UNTUK ADMIN & GURU
            // ======================================

            // Data yang sudah Anda load sebelumnya
            $viewData['phyphox'] = Phyphox::where('is_active', '1')->get();
            $viewData['data'] = Kelas::with(['modul', 'guru'])->latest()->get(); // Ini semua kelas
            $viewData['modul'] = Modul::with(['kelas', 'kelas.peserta'])->get(); // Ini semua modul

            // $viewData['guru'] = []; // Guru hanya untuk Admin (jika perlu)
            // if ($userLogin->roles->contains('name', 'Administrator')) {
            //     $viewData['guru'] = User::whereHas('roles', fn($q) => $q->where('name', 'Guru'))->get();
            // }

            // Variabel $kelas_siswa sepertinya tidak diperlukan lagi dengan logika baru
            // $viewData['kelas_siswa'] = ... ;

        } else {
            // Role lain (jika ada), mungkin tampilkan halaman kosong atau pesan error
            // Atau redirect ke halaman lain
        }

        // Kirim semua data yang relevan ke view 'home'
        return view('home', $viewData);
    }
}
