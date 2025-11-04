<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Kelas;
use App\Models\KelasUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KelasUserController extends Controller
{
    /**
     * Tampilkan daftar kelas user
     */
    public function index($id)
    {
        $userLogin = auth()->user();
        $kelas = Kelas::with(['guru', 'modul'])->findOrFail($id);

        $siswa = User::whereHas('roles', function ($q) {
            $q->where('name', 'Siswa');
            })->get();

        // dd($siswa);
        $peserta = KelasUser::with('user')
            ->where('kelas_id', $id)
            ->get();

        return view('kelas.show_participants', compact('userLogin','kelas', 'peserta', 'siswa'));
    }


    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'user_id' => 'required|exists:users,id',
            'pro_kontra_id' => 'nullable|in:1,0',
        ]);

        try {
            // 🔍 Cek apakah peserta sudah terdaftar di kelas ini
            $cekPeserta = KelasUser::where('kelas_id', $request->kelas_id)
                ->where('user_id', $request->user_id)
                ->first();

            if ($cekPeserta) {
                return redirect()->back()->with('error', 'Peserta ini sudah terdaftar di kelas tersebut!');
            }

            // 🚀 Jika belum ada, simpan data baru
            KelasUser::create([
                'kelas_id' => $request->kelas_id,
                'user_id' => $request->user_id,
                'pro_kontra_id' => $request->pro_kontra_id,
            ]);

            return redirect()->back()->with('success', 'Data peserta berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            "Content-type" => "csv",
            "Content-Disposition" => "attachment; filename=template_peserta_kelas.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['nama_kelas', 'nama_siswa'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Contoh baris isi
            fputcsv($file, ['XII IPA 1', 'BUDI SANTOSO']);
            fputcsv($file, ['XII IPA 1', 'SITI AMINAH']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    /**
     * Hapus data kelas user
     */
    public function destroy($id)
    {
        try {
            $kelasUser = KelasUser::findOrFail($id);
            $kelasUser->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }


    public function markPro(Request $request, $id)
    {

        try {
            $peserta = KelasUser::findOrFail($id);
            $peserta->update(['pro_kontra_id' => '1']);

            return redirect()->back()->with('success', 'Status pro berhasil diubah.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    public function markKontra(Request $request, $id)
    {
        try {
            $peserta = KelasUser::findOrFail($id);
            $peserta->update(['pro_kontra_id' => '0']);

            return redirect()->back()->with('success', 'Status kontra berhasil diubah.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}
