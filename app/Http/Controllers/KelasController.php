<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Modul;
use App\Models\KelasUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
}
