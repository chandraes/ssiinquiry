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
        $userLogin = auth()->user();
        $kelas = Kelas::with(['guru', 'modul'])->findOrFail($id);

        $siswa = User::whereHas('roles', function ($q) {
            $q->where('name', 'Siswa');
            })->get();

        // dd($userLogin);
        $peserta = KelasUser::with('user')
            ->where('kelas_id', $id)
            ->get();

        $pengantar = DB::table('pengantar')
            ->where('modul_id', $kelas->modul_id)
            ->first();

        $tujuan = DB::table('tujuan_modul')
            ->where('modul_id', $kelas->modul_id)
            ->get();

        $materi_awal = DB::table('materi_awal')
            ->where('modul_id', $kelas->modul_id)
            ->get();
            // dd($kelas->modul_id);

        $refleksi_awal = DB::table('refleksi_awal')
            ->where('modul_id', $kelas->modul_id)
            ->get();

        return view('kelas.peserta.index', 
        compact('kelas', 'peserta', 'siswa', 
        'userLogin', 'pengantar', 'tujuan',
        'materi_awal', 'refleksi_awal'
        ));
    }


    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'user_id' => 'required|exists:users,id',
            'pro_kontra_id' => 'required|in:0,1',
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

    /**
     * Simpan jawaban peserta untuk sebuah kelas (route name: kelas.jawaban.simpan)
     *
     * Mengharapkan payload:
     * - user_id (optional, default: auth user)
     * - jawaban => [
     *     ['pertanyaan_id' => int, 'jawaban_text' => string|null],
     *     ...
     *   ]
     */
    public function simpanJawaban(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'jawaban' => 'required|array|min:1',
            'jawaban.*.pertanyaan_id' => 'required|integer',
            'jawaban.*.jawaban_text' => 'nullable|string',
        ]);

        // pastikan kelas ada
        $kelas = Kelas::findOrFail($id);

        // gunakan user dari request jika ada, kalau tidak gunakan user yang sedang login
        $userId = $request->input('user_id', auth()->id());

        DB::beginTransaction();
        try {
            $now = now();
            $rows = [];

            foreach ($request->input('jawaban', []) as $item) {
                $rows[] = [
                    'kelas_id' => $kelas->id,
                    'user_id' => $userId,
                    'pertanyaan_id' => $item['pertanyaan_id'],
                    'jawaban' => $item['jawaban_text'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Simpan atau update jika sudah ada (menggunakan upsert)
            // key unik: kelas_id + user_id + pertanyaan_id
            DB::table('jawaban')->upsert(
                $rows,
                ['kelas_id', 'user_id', 'pertanyaan_id'],
                ['jawaban', 'updated_at']
            );

            DB::commit();
            return redirect()->back()->with('success', 'Jawaban berhasil disimpan.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
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
}
