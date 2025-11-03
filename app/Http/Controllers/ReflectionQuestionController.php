<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\ReflectionAnswer;
use Illuminate\Support\Facades\DB; // <-- [BARU] Untuk transaction
use App\Models\ReflectionQuestion;
use Illuminate\Support\Facades\Auth; // <-- [BARU] Untuk keamanan
use App\Models\SubModuleProgress;     // <-- [BARU] Untuk keamanan
use App\Models\ReflectionQuestionOption; // <-- [BARU]
use App\Http\Controllers\Controller;

class ReflectionQuestionController extends Controller
{
    /**
     * [DIPERBARUI] Menyimpan pertanyaan refleksi baru (Esai atau PG).
     */
    public function store(Request $request)
    {


        $request->validate([
            'sub_module_id' => 'required|exists:sub_modules,id',
            'type' => 'required|in:essay,multiple_choice', // <-- [BARU] Validasi tipe
            'question_text.id' => 'required|string',
            'question_text.en' => 'required|string',
            'order' => 'nullable|integer',

            // [BARU] Validasi untuk Pilihan Ganda
            // 'options' harus ada jika tipenya multiple_choice, dan harus array
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*.id' => 'required|string', // Validasi teks ID setiap opsi
            'options.*.en' => 'required|string', // Validasi teks EN setiap opsi
            // 'correct_option' adalah index dari array 'options'
            'correct_option_index' => 'required_if:type,multiple_choice|integer',
        ]);

        // Gunakan DB Transaction untuk memastikan data konsisten
        // Jika gagal simpan opsi, pertanyaan juga akan di-rollback
        DB::beginTransaction();
        try {
            // 1. Buat Pertanyaannya dulu
            $question = ReflectionQuestion::create([
                'sub_module_id' => $request->sub_module_id,
                'question_text' => $request->question_text,
                'order' => $request->order ?? 1,
                'type' => $request->type, // <-- [BARU] Simpan tipenya
            ]);

            // 2. Jika tipenya Pilihan Ganda, simpan opsinya
            if ($request->type == 'multiple_choice' && $request->has('options')) {
                foreach ($request->options as $index => $optionText) {
                    // Cek apakah ini adalah index kunci jawaban
                    $isCorrect = ($index == $request->correct_option_index);

                    // Buat opsi baru yang terhubung ke pertanyaan
                    $question->options()->create([
                        'option_text' => $optionText, // Spatie translatable akan menangani array [id, en]
                        'is_correct' => $isCorrect,
                    ]);
                }
            }

            // Jika semua berhasil, commit
            DB::commit();
            return redirect()->back()->with('success', 'Pertanyaan berhasil ditambahkan!');
        } catch (Exception $e) {
            // Jika ada error, rollback
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * [DIPERBARUI] Mengambil data satu pertanyaan sebagai JSON untuk modal edit.
     * Sekarang juga menyertakan 'type' dan 'options'.
     */
    public function edit(ReflectionQuestion $question)
    {
        // $question sudah otomatis di-resolve

        // [BARU] Muat relasi 'options' jika ada
        $question->load('options');

        // Kirim data yang lebih lengkap
        return response()->json([
            'id' => $question->id,
            'question_text' => $question->getTranslations('question_text'),
            'order' => $question->order,
            'type' => $question->type, // <-- [BARU] Kirim tipe
            'options' => $question->options, // <-- [BARU] Kirim semua opsi
        ]);
    }

    /**
     * [DIPERBARUI] Update data pertanyaan refleksi (Esai atau PG).
     */
    public function update(Request $request, ReflectionQuestion $question)
    {
        $request->validate([
            'type' => 'required|in:essay,multiple_choice',
            'question_text.id' => 'required|string',
            'question_text.en' => 'required|string',
            'order' => 'nullable|integer',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*.id' => 'required|string',
            'options.*.en' => 'required|string',
            'correct_option_index' => 'required_if:type,multiple_choice|integer',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data pertanyaan utamanya
            $question->update([
                'question_text' => $request->question_text,
                'order' => $request->order ?? 1,
                'type' => $request->type,
            ]);

            // 2. Hapus semua opsi lama (cara termudah untuk sinkronisasi)
            $question->options()->delete();

            // 3. Jika tipenya Pilihan Ganda, buat ulang opsinya
            if ($request->type == 'multiple_choice' && $request->has('options')) {
                foreach ($request->options as $index => $optionText) {
                    $isCorrect = ($index == $request->correct_option_index);
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => $isCorrect,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pertanyaan berhasil diperbarui!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pertanyaan refleksi.
     * (TIDAK BERUBAH. onDelete('cascade') di migration akan menghapus opsi)
     */
    public function destroy(ReflectionQuestion $question)
    {
        try {
            $question->delete();
            return redirect()->back()->with('success', 'Pertanyaan berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pertanyaan: ' . $e->getMessage());
        }
    }


    // ===================================================================
    // == BAGIAN SISWA (DIPERBARUI UNTUK KEAMANAN & PG)
    // ===================================================================

    /**
     * [DIPERBARUI TOTAL] Menyimpan jawaban esai ATAU pilihan ganda dari siswa.
     */
    public function saveAnswer(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'question_id' => 'required|exists:reflection_questions,id',
            'class_id' => 'required|exists:kelas,id',

            // [INI PERBAIKANNYA] 'answer_text' hanya wajib jika 'option_id' tidak ada
            'answer_text' => 'required_without:option_id|nullable|string|max:5000',

            // [INI PERBAIKANNYA] 'option_id' hanya wajib jika 'answer_text' tidak ada
            'option_id' => 'required_without:answer_text|nullable|exists:reflection_question_options,id',
        ]);

        $student = Auth::user();
        $question = ReflectionQuestion::find($request->question_id);
        $class_id = $request->class_id;

        // 2. [KEAMANAN PENGUNCIAN]
        $progress = SubModuleProgress::where('user_id', $student->id)
            ->where('sub_module_id', $question->sub_module_id)
            ->where('kelas_id', $class_id)
            ->first();

        if ($progress && $progress->completed_at) {
            return response()->json([
                'error' => 'Gagal menyimpan. Anda sudah menyelesaikan sub-modul ini.'
            ], 403); // 403 Forbidden
        }

        // 3. Tentukan data yang akan disimpan
        $dataToSave = [];
        $is_correct = null; // Status jawaban (null untuk esai)

        if ($request->has('option_id') && $request->option_id) {
            // Siswa menjawab Pilihan Ganda
            $dataToSave['reflection_question_option_id'] = $request->option_id;
            // [PERBAIKAN] Kirim string kosong agar tidak 'null'
            $dataToSave['answer_text'] = '';

            // 4. [KEAMANAN KUNCI JAWABAN]
            $selectedOption = ReflectionQuestionOption::find($request->option_id);
            $is_correct = $selectedOption->is_correct;
        } else if ($request->has('answer_text')) {
            // Siswa menjawab Esai
            $dataToSave['answer_text'] = $request->answer_text;
            $dataToSave['reflection_question_option_id'] = null;
        } else {
            return response()->json(['error' => 'Tidak ada jawaban yang dikirim.'], 422);
        }

        // 5. Simpan atau Update Jawaban
        $answer = ReflectionAnswer::updateOrCreate(
            [
                'student_id' => $student->id,
                'reflection_question_id' => $request->question_id,
                'course_class_id' => $class_id,
            ],
            $dataToSave
        );

        // 6. Return Response JSON
        return response()->json([
            'message' => 'Jawaban berhasil disimpan.',
            'is_correct' => $is_correct,
        ]);
    }
}
