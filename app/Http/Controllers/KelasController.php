<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Modul;
use App\Models\KelasUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    /**
     * Menampilkan semua data kelas
     */
    public function index()
    {
        $userLogin = auth()->user();
        $data = Kelas::with(['modul', 'guru'])->latest()->get();
        $modul = Modul::all();

        // Hanya tampilkan guru jika role user login adalah Administrator
        $guru = [];
        if (Auth::user()->role == 'Administrator') {
            $guru = User::where('role', 'guru')->get();
        }

        return view('kelas.index', compact('data', 'modul', 'guru', 'userLogin'));
    }

    public function search_guru_pengajar(Request $request)
    {
        // $userLogin = auth()->user(); // ambil user login
        $search = $request->q;

        // Query dasar pencarian user
        $query = User::where('name', 'like', "%{$search}%")
                    ->select('id', 'name')
                    ->limit(10);

        $query->whereHas('roles', function ($q) {
                $q->where('name', 'Guru');
            });

        $users = $query->get();

        return response()->json($users);
    }


    /**
     * Menyimpan data kelas baru
     */
    public function store(Request $request)
    {
        // [DIUBAH] Validasi menggunakan dot notation
        $request->validate([
            'modul_id' => 'required|exists:moduls,id',
            'nama_kelas.id' => 'required|string|max:255',
            'nama_kelas.en' => 'required|string|max:255',
        ]);

        try {
            // ... (Logika guruId Anda tidak berubah, sudah bagus) ...
            $guruId = Auth::user()->roles->contains('name', 'Guru')
                ? Auth::id()
                : $request->guru_id;

            if (Auth::user()->role == 'Administrator') {
                $request->validate([
                    'guru_id' => 'required|exists:users,id',
                ]);
            }

            // ... (Logika kodeJoin Anda tidak berubah, sudah bagus) ...
            $kodeJoin = (function() {
                do {
                    $k = strtoupper(Str::random(5));
                } while (Kelas::where('kode_join', $k)->exists());
                return $k;
            })();

            // [DIUBAH] Terapkan mb_strtoupper ke setiap bahasa
            $namaKelas = $request->nama_kelas;
            $namaKelas['id'] = mb_strtoupper($namaKelas['id'], 'UTF-8');
            $namaKelas['en'] = mb_strtoupper($namaKelas['en'], 'UTF-8');

            Kelas::create([
                'modul_id' => $request->modul_id,
                'nama_kelas' => $namaKelas, // <-- Kirim array yang sudah di-uppercase
                'owner'   => $guruId,
                'kode_join' => $kodeJoin,
            ]);

            return redirect()->back()->with('success', 'Kelas berhasil ditambahkan!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan kelas: ' . $e->getMessage());
        }
    }


    /**
     * Mengupdate data kelas
     */
    public function update(Request $request, $id)
    {
        // [DIUBAH] Validasi menggunakan dot notation
        $request->validate([
            'modul_id' => 'required|exists:moduls,id',
            'nama_kelas.id' => 'required|string|max:255',
            'nama_kelas.en' => 'required|string|max:255',
        ]);

        try {
            $kelas = Kelas::findOrFail($id);

            // ... (Logika guruId Anda tidak berubah, sudah bagus) ...
            $guruId = $guruId = Auth::user()->roles->contains('name', 'Guru')
                ? Auth::id()
                : $request->guru_id;

            if (Auth::user()->role == 'Administrator') {
                $request->validate([
                    'guru_id' => 'required|exists:users,id',
                ]);
            }

            // [DIUBAH] Terapkan mb_strtoupper ke setiap bahasa
            $namaKelas = $request->nama_kelas;
            $namaKelas['id'] = mb_strtoupper($namaKelas['id'], 'UTF-8');
            $namaKelas['en'] = mb_strtoupper($namaKelas['en'], 'UTF-8');

            $kelas->update([
                'modul_id' => $request->modul_id,
                'nama_kelas' => $namaKelas, // <-- Kirim array yang sudah di-uppercase
                'owner'   => $guruId,
                // 'kode_join' tidak perlu di-update (sudah benar)
            ]);

            return redirect()->back()->with('success', 'Kelas berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui kelas: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data kelas
     */
    public function destroy($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            $kelas->delete();

            return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }

    
    public function siswa_kelas($id)
    {
        $userLogin = auth()->user();
        // dd($id);
        // Ambil data kelas, sekaligus eager loading peserta (users)
        $kelas = Kelas::with('peserta', 'peserta.user', 'modul')->findOrFail($id);
        
        $user = Auth::user();
        
        // Periksa apakah user yang login sudah tergabung dalam kelas ini
        $isJoined = $kelas->peserta->pluck('user_id')->contains($user->id);

        // Ambil daftar peserta untuk ditampilkan
        $peserta = $kelas->peserta;
        // dd($isJoined, $kelas);

        // $pengantar = DB::table('pengantar')
        //     ->where('modul_id', $kelas->modul_id)
        //     ->first();

        // $tujuan = DB::table('tujuan_modul')
        //     ->where('modul_id', $kelas->modul_id)
        //     ->get();

        // $materi_awal = DB::table('materi_awal')
        //     ->where('modul_id', $kelas->modul_id)
        //     ->get();
        //     // dd($kelas->modul_id);

        // $refleksi_awal = DB::table('refleksi_awal')
        //     ->where('modul_id', $kelas->modul_id)
        //     ->get();

        return view('kelas.peserta.index', 
        compact('kelas', 'peserta', 'isJoined',
        'userLogin', 
        // 'pengantar', 'tujuan',
        // 'materi_awal', 'refleksi_awal'
        ));
    }

    public function siswa_join(Request $request, $kelas_id)
    {
        $request->validate([
            'kode_join' => 'required|string|exists:kelas,kode_join',
        ]);

        try {
            $kelas = Kelas::findOrFail($kelas_id);
            $user = Auth::user();

            // Periksa apakah user sudah tergabung
            $isJoined = KelasUser::where('kelas_id', $kelas->id)
                                      ->where('user_id', $user->id)
                                      ->exists();
            if ($isJoined) {
                return redirect()->back()->with('info', 'Anda sudah tergabung dalam kelas ini.');
            }

            // Tambahkan user ke kelas
            KelasUser::create([
                'kelas_id' => $kelas->id,
                'user_id' => $user->id,
            ]);

            return redirect()->back()->with('success', 'Berhasil bergabung ke kelas!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal bergabung ke kelas: ' . $e->getMessage());
        }
    }
}
