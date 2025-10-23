<?php

namespace App\Http\Controllers;

use App\Models\ReflectionQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

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
}
