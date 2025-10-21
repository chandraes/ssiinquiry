<?php

namespace App\Http\Controllers;

use App\Models\KelasUser;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Exception;

class KelasUserController extends Controller
{
    /**
     * Tampilkan daftar kelas user
     */
    public function index($id)
    {
        $kelas = Kelas::with(['guru', 'modul'])->findOrFail($id);

        $siswa = User::whereHas('roles', function ($q) {
            $q->where('name', 'Siswa');
            })->get();

        // dd($siswa);
        $peserta = KelasUser::with('user')
            ->where('kelas_id', $id)
            ->get();

        return view('kelas.peserta.index', compact('kelas', 'peserta', 'siswa'));
    }


    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'user_id' => 'required|exists:users,id',
            'pro_kontra_id' => 'nullable|in:1,2',
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

//     public function upload(Request $request)
// {
//     $request->validate([
//         'file' => 'required|file|mimetypes:text/csv,text/plain,application/vnd.ms-excel|max:2048',
//     ]);


//     // dd($request->file('file'));


//     try {
//         $file = $request->file('file');
//         $path = $file->getRealPath();

//         // Baca file CSV
//         $data = array_map('str_getcsv', file($path));
//         $inserted = 0;
//         $skipped = 0;
//         $errors = [];

//         DB::beginTransaction();

//         foreach ($data as $index => $row) {
//             if ($index == 0) continue; // Lewati header

//             $namaKelas = trim($row[0] ?? '');
//             $namaSiswa = trim($row[1] ?? '');

//             if (!$namaKelas || !$namaSiswa) {
//                 $skipped++;
//                 continue;
//             }

//             // Cari kelas berdasarkan nama_kelas
//             $kelas = Kelas::whereRaw('LOWER(nama_kelas) = ?', [strtolower($namaKelas)])->first();
//             if (!$kelas) {
//                 $errors[] = "Kelas '{$namaKelas}' tidak ditemukan.";
//                 $skipped++;
//                 continue;
//             }

//             // Cari user berdasarkan nama lengkap (nama_siswa)
//             $user = User::whereRaw('LOWER(name) = ?', [strtolower($namaSiswa)])->first();
//             if (!$user) {
//                 $errors[] = "Siswa '{$namaSiswa}' belum membuat akun.";
//                 $skipped++;
//                 continue;
//             }

//             // Cek duplikat data
//             $exists = KelasUser::where('kelas_id', $kelas->id)
//                 ->where('user_id', $user->id)
//                 ->exists();

//             if ($exists) {
//                 $skipped++;
//                 continue;
//             }

//             // Simpan data peserta
//             KelasUser::create([
//                 'kelas_id' => $kelas->id,
//                 'user_id' => $user->id,
//                 'pro_kontra_id' => null,
//             ]);

//             $inserted++;
//         }

//         DB::commit();

//         // Buat pesan sukses + error detail
//         $message = "Upload selesai! {$inserted} peserta ditambahkan, {$skipped} dilewati.";
//         if (count($errors) > 0) {
//             $message .= "Detail Kesalahan:";
//             foreach ($errors as $err) {
//                 $message .= "$err";
//             }
//             $message .= "";
//         }

//         return redirect()->back()->with('success', $message);

//     } catch (\Exception $e) {
//         DB::rollBack();
//         return redirect()->back()->with('error', 'Gagal upload peserta: ' . $e->getMessage());
//     }
// }


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
}
