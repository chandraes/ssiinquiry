<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Modul;
use App\Models\KelasUser;
use App\Models\SubModule;
use App\Models\SubModuleProgress;
use App\Models\PracticumSubmission;
use App\Models\ForumPost;
use App\Models\ReflectionAnswer;
use App\Models\ReflectionQuestion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Menampilkan dashboard kelas untuk Guru (DIPERBARUI UNTUK GRADEBOOK)
     */
    public function show(Kelas $kelas)
    {
        // 1. Muat relasi dasar kelas
        // Kita juga butuh 'modul' untuk mengambil daftar sub-modul
        $kelas->load('students', 'modul');

        // 2. Ambil semua sub-modul yang BISA DINILAI untuk modul ini
        // Kita tidak mengambil tipe 'learning' (Materi)
        $gradableSubModules = SubModule::where('modul_id', $kelas->modul_id)
            ->where('type', '!=', 'learning')
            ->orderBy('order', 'asc')
            ->get();

        // 3. Ambil SEMUA progress siswa untuk SELURUH kelas ini
        // Ini jauh lebih efisien daripada me-load progress per siswa
        $allProgress = SubModuleProgress::where('kelas_id', $kelas->id)
            // Ubah collection menjadi array asosiatif 2D
            // Kuncinya adalah "user_id"_"sub_module_id"
            ->get()
            ->keyBy(function ($item) {
                return $item->user_id . '_' . $item->sub_module_id;
            });

        // 4. Kirim data ke view
        return view('kelas.show', [
            'kelas' => $kelas,
            'students' => $kelas->students,       // Daftar siswa (untuk Baris)
            'subModules' => $gradableSubModules, // Daftar kolom tugas (untuk Kolom)
            'allProgress' => $allProgress,     // Semua data nilai (untuk Sel)
        ]);
    }

    /**
     * [BARU] Menyimpan nilai dan feedback dari modal Gradebook.
     */
    public function saveGrade(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'sub_module_id' => 'required|exists:sub_modules,id',
            'kelas_id' => 'required|exists:kelas,id',
            'score' => 'required|integer|min:0',
            'feedback' => 'nullable|string',
        ]);

        try {
            // 2. Cari SubModul untuk Poin Maks
            $subModule = SubModule::find($request->sub_module_id);
            if ($request->score > $subModule->max_points) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nilai tidak boleh melebihi poin maksimal (' . $subModule->max_points . ').'
                ], 422); // Unprocessable Entity
            }

            // 3. Update atau buat data progress
            $progress = SubModuleProgress::updateOrCreate(
                [
                    'user_id' => $request->student_id,
                    'sub_module_id' => $request->sub_module_id,
                    'kelas_id' => $request->kelas_id,
                ],
                [
                    'score' => $request->score,
                    'feedback' => $request->feedback,
                    // Jika siswa belum 'selesai' tapi dinilai, anggap selesai
                    'completed_at' => now(),
                ]
            );

            // 4. Kirim respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Nilai berhasil disimpan.',
                'new_cell_text' => $progress->score . ' / ' . $subModule->max_points,
                'student_id' => $progress->user_id,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * [DIPERBAIKI] Mengambil detail submission siswa untuk ditampilkan di modal Gradebook.
     * Menggunakan kolom 'student_id' yang benar.
     */
    public function getSubmissionDetails(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'sub_module_id' => 'required|exists:sub_modules,id',
        ]);

        $studentId = $request->query('student_id');
        $subModuleId = $request->query('sub_module_id');
        $subModule = SubModule::find($subModuleId);

        $html = '';

        try {
            switch ($subModule->type) {

                case 'reflection':
                    // (Logika ini sudah benar menggunakan student_id)
                    $questionIds = ReflectionQuestion::where('sub_module_id', $subModuleId)
                                     ->pluck('id');
                    $answers = ReflectionAnswer::where('student_id', $studentId)
                               ->whereIn('reflection_question_id', $questionIds)
                               ->with('question')
                               ->get();

                    if ($answers->isNotEmpty()) {
                        $html = '<h5>Jawaban Refleksi Siswa:</h5>';
                        $html .= '<ul class="list-group list-group-flush">';
                        foreach ($answers as $answer) {
                            $html .= '<li class="list-group-item p-2" style="font-size: 0.9em;">';
                            $html .= '<strong>' . e($answer->question->question_text ?? 'Pertanyaan') . ':</strong>';
                            $html .= '<div class="rich-text-content border-start ps-2 mt-1">' . $answer->answer_text . '</div>';
                            $html .= '</li>';
                        }
                        $html .= '</ul>';
                    } else {
                        $html = '<p class="text-muted text-center">Siswa belum mengirimkan refleksi.</p>';
                    }
                    break;

                // [BLOK YANG DIPERBAIKI]
                case 'practicum':
                    // [PERBAIKAN] Ganti 'user_id' menjadi 'student_id'
                    $submissions = PracticumSubmission::where('student_id', $studentId)
                                    ->whereHas('slot', fn($q) => $q->where('sub_module_id', $subModuleId))
                                    ->with('slot')
                                    ->get();

                    if ($submissions->isNotEmpty()) {
                        $chartData = [];
                        $fileListHtml = '<ul>';
                        foreach ($submissions as $sub) {
                            $fileListHtml .= '<li><i class="fa fa-file-csv text-success"></i> ' . e($sub->original_filename) . '</li>';
                            $url = Storage::url($sub->file_path);
                            $chartData[] = [
                                'label' => e($sub->slot->label),
                                'url' => $url
                            ];
                        }
                        $fileListHtml .= '</ul>';

                        $html = '<h5><i class="fa fa-chart-line"></i> Pratinjau Grafik</h5>';
                        $html .= '<div class="chart-container mb-3" style="position: relative; height:300px; background-color:#fff; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">';
                        $html .= '<canvas id="gradebookChartCanvas"></canvas>';
                        $html .= '</div>';
                        $html .= '<button type="button" class="btn btn-primary btn-sm w-100" id="loadGradebookChartBtn"';
                        $html .= ' data-json="' . e(json_encode($chartData)) . '">';
                        $html .= '<i class="fa fa-sync-alt"></i> Muat / Ulangi Grafik</button>';
                        $html .= '<hr><h5>File yang Diunggah:</h5>' . $fileListHtml;
                    } else {
                        $html = '<p class="text-muted text-center">Siswa belum mengunggah file praktikum.</p>';
                    }
                    break;

                // [BLOK YANG DIPERBAIKI]
                case 'forum':
                    // [PERBAIKAN] Ganti 'user_id' menjadi 'student_id'
                    $posts = ForumPost::where('student_id', $studentId)
                                ->where('sub_module_id', $subModuleId)
                                ->orderBy('created_at', 'asc')
                                ->get();

                    if ($posts->isNotEmpty()) {
                        $html = '<h5>Aktivitas Forum Siswa (' . $posts->count() . ' post):</h5>';
                        $html .= '<ul class="list-group list-group-flush">';
                        foreach ($posts as $post) {
                            $html .= '<li class="list-group-item p-2" style="font-size: 0.9em;">';
                            $html .= '<strong class="text-muted">' . $post->created_at->format('d M Y, H:i') . ':</strong>';
                            $html .= '<div class="rich-text-content border-start ps-2">' . $post->content . '</div>';
                            $html .= '</li>';
                        }
                        $html .= '</ul>';
                    } else {
                        $html = '<p class="text-muted text-center">Siswa belum berpartisipasi di forum.</p>';
                    }
                    break;

                default:
                    $html = '<p class="text-muted text-center">Tipe sub-modul ini tidak memiliki pratinjau submission.</p>';
            }

            return response()->json(['html' => $html]);

        } catch (Exception $e) {
            return response()->json([
                'html' => '<div class="alert alert-danger">Gagal memuat data: ' . $e->getMessage() . '</div>'
            ], 500);
        }
    }

    public function showForums(Kelas $kelas)
    {
        // 1. Muat modul yang terkait dengan kelas ini
        $kelas->load('modul');

        // 2. Ambil semua sub-modul dari modul tersebut HANYA yang tipenya 'forum'
        $forumSubModules = $kelas->modul->subModules()
                                ->where('type', 'forum')
                                ->orderBy('order')
                                ->get();

        // 3. Kirim data ke view hub
        return view('kelas.show_forums', compact('kelas', 'forumSubModules'));
    }
}
