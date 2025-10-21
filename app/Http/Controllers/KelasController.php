<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Modul;
use App\Models\KelasUser;
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
        // dd(Auth::user()->roles);
        $request->validate([
            'modul_id' => 'required|exists:moduls,id',
            'nama_kelas' => 'required|string|max:255',
        ]);

        try {
            // Jika user login adalah guru → gunakan id-nya sendiri
            // Jika Administrator → gunakan guru_id dari inputan form
            $guruId = Auth::user()->roles->contains('name', 'Guru')
                ? Auth::id()
                : $request->guru_id;

            if (Auth::user()->role == 'Administrator') {
                $request->validate([
                    'guru_id' => 'required|exists:users,id',
                ]);
            }
            
            // dd($guruId);

            Kelas::create([
                'modul_id' => $request->modul_id,
                'nama_kelas' => mb_strtoupper($request->nama_kelas, 'UTF-8'),
                'guru_id'   => $guruId,
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
        $request->validate([
            'modul_id' => 'required|exists:moduls,id',
            'nama_kelas' => 'required|string|max:255',
        ]);

        try {
            $kelas = Kelas::findOrFail($id);

            $guruId = $guruId = Auth::user()->roles->contains('name', 'Guru')
                ? Auth::id()
                : $request->guru_id;

            if (Auth::user()->role == 'Administrator') {
                $request->validate([
                    'guru_id' => 'required|exists:users,id',
                ]);
            }

            $kelas->update([
                'modul_id' => $request->modul_id,
                'nama_kelas' => $request->nama_kelas,
                'guru_id'   => $guruId,
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
