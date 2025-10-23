<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Modul;
use App\Models\Phyphox;
use App\Models\KelasUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $phyphox = Phyphox::where('is_active', '1')->get();
        $userLogin = auth()->user();
        $data = Kelas::with(['modul', 'guru'])->latest()->get();
        $modul = Modul::with(['kelas', 'kelas.peserta'])->get();

        // Hanya tampilkan guru jika role user login adalah Administrator
        $guru = [];
        if (Auth::user()->role == 'Administrator') {
            $guru = User::where('role', 'guru')->get();
        }

        $kelas_siswa = Modul::with('kelas.peserta', 'kelas')
                ->get();

        $modul_siswa = collect();
        if (auth()->check()) {
            $user = auth()->user();

            // cek apakah user berperan 'siswa' (support hasRole() atau atribut role)
            $isSiswa = (method_exists($user, 'hasRole') && $user->hasRole('siswa')) || (($user->role ?? null) === 'siswa');

            if ($isSiswa) {
                // coba ambil peserta yang berelasi dengan user; jika tidak ada, cari lewat user_id
                $peserta = $user->peserta ?? KelasUser::where('user_id', $user->id)->first();

                if ($peserta) {
                    $modul_siswa = Modul::whereHas('kelas.peserta', function ($q) use ($peserta) {
                        $q->where('id', $peserta->id);
                    })->with('kelas', 'kelas.peserta')->get();

                    // tampilkan hanya modul yang terkait dengan siswa
                    $moduls = $modul_siswa;
                } else {
                    // jika tidak ada peserta, kosongkan hasil untuk siswa
                    $moduls = collect();
                }
            }
        }
        
        // dd($modul, $modul_siswa);
                // dd($phyphox);
        return view('home', compact('phyphox','data', 'modul', 'guru', 'userLogin', 'kelas_siswa'));
    }

    public function landing_page()
    {
        return view('index');
    }

    public function admin()
    {
        $user=Auth::user();
        dd($user);
        return view('layout.admin', compact('user'));
    }
}
