<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\ReflectionAnswer;
use App\Models\ReflectionQuestion;
use App\Http\Controllers\Controller;

class ReflectionQuestionController extends Controller
{
    /**
     * Menyimpan pertanyaan refleksi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sub_module_id' => 'required|exists:sub_modules,id',
            'question_text.id' => 'required|string',
            'question_text.en' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        try {
            ReflectionQuestion::create($request->all());
            return redirect()->back()->with('success', 'Pertanyaan berhasil ditambahkan!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Mengambil data satu pertanyaan sebagai JSON untuk modal edit.
     */
    public function edit(ReflectionQuestion $question)
    {
        // $question sudah otomatis di-resolve oleh Route Model Binding
        return response()->json([
            'id' => $question->id,
            'question_text' => $question->getTranslations('question_text'), // {"id": "...", "en": "..."}
            'order' => $question->order,
        ]);
    }

    /**
     * Update data pertanyaan refleksi.
     */
    public function update(Request $request, ReflectionQuestion $question)
    {
         $request->validate([
            'question_text.id' => 'required|string',
            'question_text.en' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        try {
            $question->update($request->all());
            return redirect()->back()->with('success', 'Pertanyaan berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus pertanyaan refleksi.
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

    public function storeAnswer(Request $request)
    {
        // 1. Validasi Request
        $request->validate([
            // question_id di Blade menggunakan name="question_{{ $question->id }}"
            'question_id' => 'required|exists:reflection_questions,id',
            
            // answer_text adalah inputan textarea
            'answer_text' => 'required|string|max:5000',
            
            // [PENTING] Validasi course_class_id
            // Nilai ini harus dikirimkan dari Blade (misalnya dari SubModul atau Kelas yang sedang aktif)
            'class_id' => 'nullable|exists:kelas_users,kelas_id', 
        ]);

        $userId = auth()->user()->id;

        // 2. Simpan atau Update Jawaban (berdasarkan 3 kunci unik)
        $answer = ReflectionAnswer::updateOrCreate(
            // Kunci Pencarian (untuk menentukan apakah jawaban sudah ada)
            [
                'student_id' => $userId, // User ID yang sedang login
                'reflection_question_id' => $request->question_id,
                'course_class_id' => $request->class_id, // Kunci baru dari request
            ],
            // Data yang akan disimpan/diperbarui
            [
                'answer_text' => $request->answer_text,
            ]
        );

        // 3. Return Response JSON untuk AJAX
        return response()->json([
            'message' => 'Jawaban berhasil disimpan.',
            'answer' => $answer
        ], 200);
    }
}
