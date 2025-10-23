<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Modul;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Phyphox;
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
        $phyphox = Phyphox::where('is_active', 1)->get();
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
