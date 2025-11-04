<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
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
use Mews\Purifier\Facades\Purifier;

class StudentController extends Controller
{

    // Ambil daftar kelas berdasarkan modul
    public function getAvailableClasses($modulId)
    {
        $locale = App::getLocale(); // Bahasa aktif, contoh: "id" atau "en"

        $kelasList = Kelas::where('modul_id', $modulId)
            ->get()
            ->map(function ($kelas) use ($locale) {
                return [
                    'id' => $kelas->id,
                    'kode_join' => $kelas->kode_join,
                    'nama_kelas' => $kelas->getTranslation('nama_kelas', $locale), // ✅ ambil sesuai locale
                    'translations' => $kelas->getTranslations('nama_kelas'),       // ✅ tampilkan semua bahasa
                    'created_at' => $kelas->created_at,
                    'updated_at' => $kelas->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'kelas' => $kelasList,
        ]);
    }

    public function joinClass(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'kode_join' => 'required|string',
        ]);

        $user = Auth::user();
        $kelasId = $request->kelas_id;
        $inputKode = trim($request->kode_join);

        // Ambil data kelas
        $kelas = Kelas::find($kelasId);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.'
            ]);
        }

        // ✅ Validasi kode join
        if ($kelas->kode_join !== $inputKode) {
            return response()->json([
                'success' => false,
                'message' => 'Kode join yang Anda masukkan salah.'
            ]);
        }

        // ✅ Cek apakah sudah bergabung sebelumnya
        if ($user->kelas()->where('kelas_id', $kelasId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah tergabung di kelas ini.'
            ]);
        }

        // ✅ Join kelas (pivot table: kelas_user)
        $user->kelas()->attach($kelasId);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil bergabung ke kelas!'
        ]);
    }
    
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

        // (Logika $progress awal Anda dihapus karena $currentProgress nanti akan mengambilnya)

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
        if ($subModule->order > 1) {
            $previousSubModule = SubModule::where('modul_id', $subModule->modul_id)
                                        ->where('order', '<', $subModule->order)
                                        ->orderBy('order', 'desc')
                                        ->first();
            if ($previousSubModule) {
                $previousProgress = SubModuleProgress::where('user_id', $student->id)
                                    ->where('kelas_id', $kelas->id)
                                    ->where('sub_module_id', $previousSubModule->id)
                                    ->whereNotNull('completed_at')
                                    ->first();
                if (!$previousProgress) {
                    $isLocked = true;
                }
            }
        }
        if ($isLocked) {
            return redirect()->route('student.class.show', $kelas->id)
                             ->with('error', 'Anda harus menyelesaikan sub-modul sebelumnya terlebih dahulu.');
        }

        // ==========================================================
        // 3. [PENGAMBILAN DATA] Ambil progress untuk sub-modul SAAT INI
        // ==========================================================
        // [PERBAIKAN] Gunakan firstOrCreate untuk membuat progress jika belum ada
        $currentProgress = SubModuleProgress::firstOrCreate(
            [
                'user_id' => $student->id,
                'sub_module_id' => $subModule->id,
                'kelas_id' => $kelas->id
            ]
        );

        $viewData = [
            'kelas' => $kelas,
            'subModule' => $subModule,
            'currentProgress' => $currentProgress,
        ];

        // ==========================================================
        // 4. [LOGIKA ROUTER] Tampilkan view berdasarkan tipe sub-modul
        // ==========================================================

        if ($subModule->type == 'learning') {
            $subModule->load('learningMaterials');
            return view('student.show_learning', $viewData);

        } elseif ($subModule->type == 'reflection') {

            $subModule->load('reflectionQuestions.options'); // Muat pertanyaan & opsinya

            $existingAnswers = ReflectionAnswer::where('student_id', $student->id)
                ->where('course_class_id', $kelas->id)
                ->whereIn('reflection_question_id', $subModule->reflectionQuestions->pluck('id'))
                ->get()
                ->keyBy('reflection_question_id');

            // [PERBAIKAN ERROR] Ganti nama 'existingAnswers' menjadi 'myAnswers'
            $viewData['myAnswers'] = $existingAnswers; // <-- INI PERBAIKANNYA

            return view('student.show_reflection', $viewData);

        } elseif ($subModule->type == 'practicum') {
            $subModule->load('learningMaterials', 'practicumUploadSlots');
            $existingSubmissions = PracticumSubmission::where('student_id', $student->id)
                ->where('course_class_id', $kelas->id)
                ->whereIn('practicum_upload_slot_id', $subModule->practicumUploadSlots->pluck('id'))
                ->get()
                ->keyBy('practicum_upload_slot_id');
                
            $viewData['submissions'] = $existingSubmissions; // Ganti nama agar konsisten

            return view('student.show_practicum', $viewData);

        } elseif ($subModule->type == 'forum') {
            $teamInfo = $student->forumTeams()
                                ->where('sub_module_id', $subModule->id)
                                ->where('kelas_id', $kelas->id)
                                ->first();
            $viewData['teamInfo'] = $teamInfo;
            $posts = ForumPost::where('sub_module_id', $subModule->id)
                ->where('kelas_id', $kelas->id)
                ->whereNull('parent_post_id')
                ->with([
                    'user',
                    'evidence.slot',
                    'replies' => function ($query) {
                        $query->with(['user', 'evidence.slot'])->orderBy('created_at', 'asc');
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->get();
            $viewData['posts'] = $posts;
            $mySubmissions = PracticumSubmission::where('student_id', $student->id)
                ->where('course_class_id', $kelas->id)
                ->get();
            $viewData['mySubmissions'] = $mySubmissions;
            return view('student.show_forum', $viewData);
        }

        // ==========================================================
        // 5. [FALLBACK]
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
        $action = $request->input('action'); // 'complete' or 'save_and_next'

        try {
            // 1. Validasi HANYA jika aksinya "complete"
            if ($action == 'complete') {

                // === [VALIDASI BARU DIMULAI] ===

                // 1. Dapatkan semua pertanyaan (ID dan Tipe) untuk sub-modul ini
                $allQuestions = $subModule->reflectionQuestions()
                                        ->select('id', 'type')
                                        ->get();
                $totalQuestions = $allQuestions->count();

                // 2. Hanya validasi jika ada pertanyaan
                if ($totalQuestions > 0) {

                    // 3. Dapatkan semua jawaban siswa yang valid untuk pertanyaan tsb
                    $allAnswers = ReflectionAnswer::where('student_id', $student->id)
                        ->where('course_class_id', $kelas->id)
                        ->whereIn('reflection_question_id', $allQuestions->pluck('id'))
                        ->get();

                    // 4. Hitung jawaban yang valid
                    $validAnswersCount = 0;
                    foreach($allQuestions as $question) {
                        // Cari jawaban untuk pertanyaan ini
                        $answer = $allAnswers->firstWhere('reflection_question_id', $question->id);

                        if ($answer) { // Jika jawaban ada
                            if ($question->type == 'essay') {
                                // Untuk esai, cek answer_text tidak kosong
                                if (!empty(trim($answer->answer_text))) {
                                    $validAnswersCount++;
                                }
                            } elseif ($question->type == 'multiple_choice') {
                                // Untuk PG, cek option_id tidak null
                                if ($answer->reflection_question_option_id !== null) {
                                    $validAnswersCount++;
                                }
                            }
                        }
                    } // akhir loop foreach

                    // 5. Bandingkan jumlahnya
                    if ($validAnswersCount < $totalQuestions) {
                        // Jika jumlah jawaban valid < jumlah pertanyaan
                        $missingCount = $totalQuestions - $validAnswersCount;
                        return redirect()->back()
                                         ->with('error', "Anda harus menjawab semua {$totalQuestions} pertanyaan terlebih dahulu. {$missingCount} pertanyaan belum terjawab.");
                    }
                }

                // === [VALIDASI BARU SELESAI] ===

                // 6. Tandai sub-modul sebagai selesai (HANYA jika validasi lolos)
                SubModuleProgress::updateOrCreate(
                    [ 'user_id' => $student->id, 'kelas_id' => $kelas->id, 'sub_module_id' => $subModule->id ],
                    [ 'completed_at' => now() ] // Kunci jawaban siswa
                );
            }

            // 7. Tentukan Redirect (Berlaku untuk 'complete' DAN 'save_and_next')

            $nextSubModule = SubModule::where('modul_id', $subModule->modul_id)
                                    ->where('order', '>', $subModule->order)
                                    ->orderBy('order', 'asc')
                                    ->first();

            if ($nextSubModule) {
                $pesan = ($action == 'complete') ? 'Refleksi selesai! Lanjut ke materi berikutnya.' : 'Progres disimpan, lanjut ke materi berikutnya.';
                return redirect()->route('siswa.submodul.show', [$kelas->id, $nextSubModule->id])
                                 ->with('success', $pesan);
            } else {
                $pesan = ($action == 'complete') ? 'Selamat! Anda telah menyelesaikan modul ini.' : 'Progres disimpan.';
                return redirect()->route('student.class.show', $kelas->id)
                                 ->with('success', $pesan);
            }

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

        // Tentukan 'error bag' berdasarkan apakah ini balasan atau bukan
        $isReply = $request->has('parent_post_id') && $request->parent_post_id != null;
        $errorBag = $isReply ? 'replyPost' : 'mainPost';

        // 1. Validasi
        $request->validate([
            'content' => 'required|string|min:10',
            'parent_post_id' => 'nullable|exists:forum_posts,id',
            'evidence_ids' => 'nullable|array',
            'evidence_ids.*' => 'exists:practicum_submissions,id',
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
            return redirect(route('student.submodule.show', [$kelas->id, $subModule->id]) . '#post-' . $newPost->id)
                         ->with('success', 'Postingan Anda berhasil dikirim!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // [BARU] Tangkap error validasi dan kirim ke 'error bag' yang benar
            return redirect()->back()->withInput()->withErrors($e->errors(), $errorBag);

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * [BARU] Menampilkan halaman Rangkuman Nilai (Transkrip) untuk siswa.
     */
    public function showMyGrades(Kelas $kelas)
    {
        $student = Auth::user();

        // 1. [KEAMANAN] Pastikan siswa terdaftar di kelas ini
        if (!$student->kelas->contains($kelas->id)) {
            return redirect()->route('home')
                             ->with('error', 'Anda tidak terdaftar di kelas tersebut.');
        }

        // 2. Muat modul terkait
        $kelas->load('modul');

        // 3. Dapatkan semua sub-modul yang BISA DINILAI (bukan 'learning')
        $gradableSubModules = SubModule::where('modul_id', $kelas->modul->id)
            ->where('type', '!=', 'learning')
            ->orderBy('order', 'asc')
            ->get();

        // 4. Dapatkan SEMUA progress siswa untuk sub-modul yang bisa dinilai
        $allProgress = SubModuleProgress::where('user_id', $student->id)
            ->where('kelas_id', $kelas->id)
            ->whereIn('sub_module_id', $gradableSubModules->pluck('id'))
            ->get()
            ->keyBy('sub_module_id'); // Jadikan ID sub-modul sebagai key

        // 5. Hitung total
        // Kita hanya menghitung poin maks dari sub-modul yang SUDAH DINILAI
        // Ini lebih adil jika guru belum menilai semua tugas

        $totalScore = 0;
        $totalMaxPoints = 0;

        foreach($gradableSubModules as $subModule) {
            $progress = $allProgress->get($subModule->id);

            // Hanya hitung jika sudah dinilai (score tidak null)
            if ($progress && $progress->score !== null) {
                $totalScore += $progress->score;
                $totalMaxPoints += $subModule->max_points;
            }
        }

        // 6. Kirim data ke view baru
        return view('student.my_grades', [
            'kelas' => $kelas,
            'subModules' => $gradableSubModules, // Daftar semua tugas
            'allProgress' => $allProgress,    // Data nilai siswa
            'totalScore' => $totalScore,
            'totalMaxPoints' => $totalMaxPoints,
        ]);
    }
}
