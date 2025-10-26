<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumTeam;
use App\Models\Kelas;
use App\Models\PracticumSubmission;
use App\Models\PracticumUploadSlot;
use App\Models\ReflectionAnswer;
use App\Models\SubModule;
use App\Models\SubModuleProgress; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

class StudentController extends Controller
{
    /**
     * Menampilkan halaman detail kelas (kurikulum) untuk seorang siswa.
     */
    public function showClass(Kelas $kelas)
    {
        $student = Auth::user();

        // 1. Keamanan: Pastikan siswa terdaftar
        if (!$student->kelas->contains($kelas->id)) {
            return redirect()->route('home')
                             ->with('error', 'Anda tidak terdaftar di kelas tersebut.');
        }

        // 2. Ambil data kurikulum (sub-modul), diurutkan
        $kelas->load(['modul.subModules' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);
        $subModules = $kelas->modul->subModules; // Ambil koleksi sub-modul

        // 3. [BARU] Ambil data progress siswa HANYA untuk sub-modul di kelas ini
        // Kita ambil semua progress siswa untuk kelas ini, lalu ubah jadi array
        // dengan key = sub_module_id agar mudah diakses di view
        $progressData = SubModuleProgress::where('user_id', $student->id)
                            ->where('kelas_id', $kelas->id)
                            ->whereIn('sub_module_id', $subModules->pluck('id')) // Hanya sub-modul di modul ini
                            ->get()
                            ->keyBy('sub_module_id'); // Hasilnya: [sub_module_id => ProgressObject, ...]

        // 4. Kirim data ke view
        return view('student.class_show', [
            'kelas' => $kelas,
            'modul' => $kelas->modul,
            'subModules' => $subModules,
            'progressData' => $progressData, // <-- Kirim data progress
        ]);
    }

    public function showSubModule(Kelas $kelas, SubModule $subModule)
    {
        $student = Auth::user();

        // ==========================================================
        // 1. [KEAMANAN] Pastikan siswa terdaftar di kelas ini
        // ==========================================================
        if (!$student->kelas->contains($kelas->id)) {
            return redirect()->route('home')
                             ->with('error', 'Anda tidak terdaftar di kelas tersebut.');
        }

        // ==========================================================
        // 2. [KEAMANAN PRASYARAT] Cek apakah sub-modul ini terkunci
        // ==========================================================
        $isLocked = false;

        // Cek hanya jika ini BUKAN sub-modul pertama (order > 1)
        if ($subModule->order > 1) {

            // Cari sub-modul sebelumnya berdasarkan 'order'
            $previousSubModule = SubModule::where('modul_id', $subModule->modul_id)
                                        ->where('order', '<', $subModule->order)
                                        ->orderBy('order', 'desc') // Ambil yang paling dekat
                                        ->first();

            if ($previousSubModule) {
                // Cek apakah progress untuk sub-modul sebelumnya sudah "completed_at"
                $previousProgress = SubModuleProgress::where('user_id', $student->id)
                                    ->where('kelas_id', $kelas->id)
                                    ->where('sub_module_id', $previousSubModule->id)
                                    ->whereNotNull('completed_at') // Kuncinya di sini
                                    ->first();

                if (!$previousProgress) {
                    // Jika tidak ada progress selesai, modul ini TERKUNCI
                    $isLocked = true;
                }
            }
        }

        // Jika terkunci, tendang kembali ke kurikulum
        if ($isLocked) {
            return redirect()->route('student.class.show', $kelas->id)
                             ->with('error', 'Anda harus menyelesaikan sub-modul sebelumnya terlebih dahulu.');
        }

        // ==========================================================
        // 3. [PENGAMBILAN DATA] Ambil progress untuk sub-modul SAAT INI
        // ==========================================================
        $currentProgress = SubModuleProgress::where('user_id', $student->id)
                                ->where('kelas_id', $kelas->id)
                                ->where('sub_module_id', $subModule->id)
                                ->first();

        // Siapkan data dasar untuk dikirim ke semua view
        $viewData = [
            'kelas' => $kelas,
            'subModule' => $subModule,
            'currentProgress' => $currentProgress, // Untuk tombol "Tandai Selesai"
        ];

        // ==========================================================
        // 4. [LOGIKA ROUTER] Tampilkan view berdasarkan tipe sub-modul
        // ==========================================================

        if ($subModule->type == 'learning') {
            // Tipe: Materi (Video, Teks, dll)
            $subModule->load('learningMaterials'); // Muat relasi materi
            return view('student.show_learning', $viewData);

        } elseif ($subModule->type == 'reflection') {
            // Tipe: Pertanyaan Refleksi

            // [PERIKSA DI SINI] Pastikan Anda memuat relasi ini
            $subModule->load('reflectionQuestions');

            // Ambil jawaban yang sudah ada untuk siswa & kelas ini
            $existingAnswers = ReflectionAnswer::where('student_id', $student->id)
                ->where('course_class_id', $kelas->id)
                // [PENTING] Kita butuh ->reflectionQuestions yang sudah diload untuk baris ini
                ->whereIn('reflection_question_id', $subModule->reflectionQuestions->pluck('id'))
                ->get()
                ->keyBy('reflection_question_id');

            $viewData['existingAnswers'] = $existingAnswers; // Kirim ke view

            return view('student.show_reflection', $viewData);
        } elseif ($subModule->type == 'practicum') {
        // Tipe: Praktikum Phyphox
            $subModule->load('learningMaterials', 'practicumUploadSlots');

            // [DIUBAH] Gunakan student_id dan course_class_id
            $existingSubmissions = PracticumSubmission::where('student_id', $student->id)
                ->where('course_class_id', $kelas->id) // <-- BENAR
                ->whereIn('practicum_upload_slot_id', $subModule->practicumUploadSlots->pluck('id'))
                ->get()
                ->keyBy('practicum_upload_slot_id');

            $viewData['existingSubmissions'] = $existingSubmissions;

            return view('student.show_practicum', $viewData);

        } elseif ($subModule->type == 'forum') {
            // Tipe: Forum Debat
            // Ambil info tim siswa (kode ini sudah Anda miliki)
            $teamInfo = $student->forumTeams()
                                ->where('sub_module_id', $subModule->id)
                                ->where('kelas_id', $kelas->id)
                                ->first();

            $viewData['teamInfo'] = $teamInfo;

            // [BARU] Ambil semua postingan untuk forum & kelas ini
            // Kita hanya ambil postingan level atas (parent_post_id = null)
            // Lalu kita eager load balasan, user, dan buktinya.
            $posts = ForumPost::where('sub_module_id', $subModule->id)
                ->where('kelas_id', $kelas->id)
                ->whereNull('parent_post_id') // Hanya ambil postingan utama
                ->with([
                    'user', // User pembuat postingan
                    'evidence.submission', // Bukti & file submission-nya
                    'replies' => function ($query) {
                        // Muat juga balasan, user, dan bukti dari balasan
                        $query->with(['user', 'evidence.submission'])->orderBy('created_at', 'asc');
                    }
                ])
                ->orderBy('created_at', 'desc') // Postingan utama terbaru di atas
                ->get();

            $viewData['posts'] = $posts;

            // [BARU] Ambil semua file praktikum siswa di kelas ini
            $mySubmissions = PracticumSubmission::where('student_id', $student->id)
                ->where('course_class_id', $kelas->id)
                ->get();

            $viewData['mySubmissions'] = $mySubmissions;

            return view('student.show_forum', $viewData);
        }

        // ==========================================================
        // 5. [FALLBACK] Jika tipe tidak dikenal
        // ==========================================================
        return redirect()->route('student.class.show', $kelas->id)
                         ->with('error', 'Tipe sub-modul tidak dikenali.');
    }

    /**
     * [BARU] Menandai sub-modul sebagai selesai.
     */
    public function markAsComplete(Request $request, Kelas $kelas, SubModule $subModule)
    {
        $student = Auth::user();

        try {
            // Gunakan updateOrCreate untuk membuat atau memperbarui catatan progres
            // Ini aman jika siswa mengklik tombol berkali-kali
            SubModuleProgress::updateOrCreate(
                [
                    'user_id' => $student->id,
                    'kelas_id' => $kelas->id,
                    'sub_module_id' => $subModule->id,
                ],
                [
                    'completed_at' => now() // Set waktu selesai ke "sekarang"
                ]
            );

            // [PENTING] Cek sub-modul berikutnya
            $nextSubModule = SubModule::where('modul_id', $subModule->modul_id)
                                    ->where('order', '>', $subModule->order)
                                    ->orderBy('order', 'asc')
                                    ->first();

            if ($nextSubModule) {
                // Jika ada, langsung arahkan siswa ke sana
                return redirect()->route('student.submodule.show', [$kelas->id, $nextSubModule->id])
                                 ->with('success', 'Berhasil! Lanjut ke materi berikutnya.');
            } else {
                // Jika ini yang terakhir, kembalikan ke kurikulum
                return redirect()->route('student.class.show', $kelas->id)
                                 ->with('success', 'Selamat! Anda telah menyelesaikan modul ini.');
            }

        } catch (Exception $e) {
            // Jika ada error database
            return redirect()->back()->with('error', 'Gagal menyimpan progres: ' . $e->getMessage());
        }
    }

    /**
     * [DIUBAH] Menyimpan jawaban (Draf) ATAU Menyelesaikan (Lengkap).
     */
    public function storeReflection(Request $request, Kelas $kelas, SubModule $subModule)
    {
        $student = Auth::user();
        $action = $request->input('action', 'save_draft');

        try {
            // 1. Validasi berdasarkan Aksi
            if ($action == 'complete') {
                // =========================
                // AKSI: SELESAI & LANJUTKAN
                // =========================

                // [KEMBALI KE VALIDATE] Validasi ini akan gagal jika ada yg kosong
                // Ini akan otomatis redirect-back dengan 'old()' dan '$errors'
                $request->validate([
                    'answers' => 'required|array',
                    'answers.*' => 'required|string',
                ], [
                    // Kita tidak akan menampilkan pesan ini,
                    // tapi ini yang akan memicu error highlighting
                    'answers.*.required' => 'Wajib diisi.',
                ]);

            } else {
                // =========================
                // AKSI: SIMPAN DRAF
                // =========================

                // Validasi longgar, boleh kosong
                $request->validate([
                    'answers' => 'required|array',
                    'answers.*' => 'nullable|string',
                ]);
            }

            // 2. SELALU Simpan Jawaban
            // Kode ini hanya berjalan jika validasi (di atas) lolos
            foreach ($request->answers as $questionId => $answerText) {
                ReflectionAnswer::updateOrCreate(
                    [
                        'reflection_question_id' => $questionId,
                        'student_id' => $student->id,
                        'course_class_id' => $kelas->id,
                    ],
                    [
                        'answer_text' => $answerText ?? ''
                    ]
                );
            }

            // 3. Tentukan Redirect (hanya jika lolos)
            if ($action == 'save_draft') {
                return redirect()->back()->with('success', 'Draf jawaban Anda telah disimpan.');
            }

            // --- Jika action == 'complete' dan validasi lolos ---

            // Tandai sub-modul sebagai selesai
            SubModuleProgress::updateOrCreate(
                [ 'user_id' => $student->id, 'kelas_id' => $kelas->id, 'sub_module_id' => $subModule->id ],
                [ 'completed_at' => now() ]
            );

            // Cari sub-modul berikutnya
            $nextSubModule = SubModule::where('modul_id', $subModule->modul_id)
                                    ->where('order', '>', $subModule->order)
                                    ->orderBy('order', 'asc')
                                    ->first();

            if ($nextSubModule) {
                return redirect()->route('student.submodule.show', [$kelas->id, $nextSubModule->id])
                                 ->with('success', 'Jawaban refleksi tersimpan! Lanjut ke materi berikutnya.');
            } else {
                return redirect()->route('student.class.show', $kelas->id)
                                 ->with('success', 'Selamat! Jawaban refleksi tersimpan dan Anda telah menyelesaikan modul ini.');
            }

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    public function storePracticum(Request $request, Kelas $kelas, SubModule $subModule, PracticumUploadSlot $slot)
    {
        $student = Auth::user();

        // 1. Validasi file (Kode ini sudah benar)
        $request->validate([
            'practicum_file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'practicum_file.required' => 'Anda harus memilih file untuk diunggah.',
            'practicum_file.mimes' => 'File harus berekstensi .csv atau .txt.',
            'practicum_file.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);

        try {
            // 2. Simpan file (Kode ini sudah benar)
            $path = $request->file('practicum_file')->storeAs(
                'practicum/kelas_' . $kelas->id . '/user_' . $student->id,
                'slot_' . $slot->id . '_' . $request->file('practicum_file')->getClientOriginalName(),
                'public'
            );

            // 3. Simpan data ke database
            // [DIUBAH] Gunakan student_id dan course_class_id
            PracticumSubmission::updateOrCreate(
                [
                    // Keys untuk mencari:
                    'student_id' => $student->id, // <-- BENAR
                    'course_class_id' => $kelas->id, // <-- BENAR
                    'practicum_upload_slot_id' => $slot->id,
                ],
                [
                    // Data untuk di-update atau di-create:
                    'file_path' => $path,
                    'original_filename' => $request->file('practicum_file')->getClientOriginalName(),
                ]
            );

            // 4. [LOGIKA SELESAI OTOMATIS]
            $requiredSlotIds = $subModule->practicumUploadSlots->pluck('id');

            // [DIUBAH] Gunakan student_id dan course_class_id
            $userSubmissionCount = PracticumSubmission::where('student_id', $student->id) // <-- BENAR
                ->where('course_class_id', $kelas->id) // <-- BENAR
                ->whereIn('practicum_upload_slot_id', $requiredSlotIds)
                ->count();

            if ($userSubmissionCount >= $requiredSlotIds->count()) {
                // LENGKAP! Tandai sub-modul selesai
                SubModuleProgress::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'kelas_id' => $kelas->id,
                        'sub_module_id' => $subModule->id,
                    ],
                    [ 'completed_at' => now() ]
                );
            }

            // 5. Kembalikan ke halaman praktikum
            return redirect()->back()->with('success', 'File ' . $request->file('practicum_file')->getClientOriginalName() . ' berhasil diunggah!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
        }
    }

    public function storeForumPost(Request $request, Kelas $kelas, SubModule $subModule)
    {
        $student = Auth::user();

        // 1. Validasi
        $request->validate([
            'content' => 'required|string|min:10',
            'parent_post_id' => 'nullable|exists:forum_posts,id',
            'evidence_ids' => 'nullable|array', // Validasi array bukti
            'evidence_ids.*' => 'exists:practicum_submissions,id', // Pastikan ID-nya ada
        ], [
            'content.required' => 'Postingan tidak boleh kosong.',
            'content.min' => 'Postingan Anda terlalu pendek (minimal 10 karakter).',
            'evidence_ids.*.exists' => 'File bukti yang Anda pilih tidak valid.',
        ]);

        // 2. Dapatkan tim siswa
        $teamInfo = ForumTeam::where('user_id', $student->id)
                            ->where('kelas_id', $kelas->id)
                            ->where('sub_module_id', $subModule->id)
                            ->first();

        if (!$teamInfo) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di tim Pro atau Kontra.');
        }

        try {
            // 3. Buat postingan
            $newPost = ForumPost::create([
                'sub_module_id' => $subModule->id,
                'user_id' => $student->id,
                'kelas_id' => $kelas->id,
                'team' => $teamInfo->team,
                'content' => Purifier::clean($request->content),
                'parent_post_id' => $request->parent_post_id,
                'visibility' => 'public',
            ]);

            // 4. [BARU] Lampirkan bukti (jika ada)
            if ($request->has('evidence_ids')) {
                // 'attach' akan mengisi tabel pivot 'forum_post_evidence'
                $newPost->evidence()->attach($request->evidence_ids);
            }

            // 5. Kembalikan ke halaman forum
            return redirect()->route('student.submodule.show', [$kelas->id, $subModule->id])
                             ->with('success', 'Postingan Anda berhasil dikirim!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
