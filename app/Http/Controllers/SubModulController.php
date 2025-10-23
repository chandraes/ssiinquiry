<?php

namespace App\Http\Controllers;

use App\Models\SubModule;
use App\Models\LearningMaterial; // Kita akan butuh ini nanti
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\View;
class SubModulController extends Controller
{
    /**
     * Menyimpan sub-modul baru yang dikirim dari modal create.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // [DIUBAH] Tambahkan validasi untuk tipe 'forum'
        $request->validate([
            'modul_id' => 'required|exists:moduls,id',
            'type' => 'required|in:learning,reflection,practicum,forum', // Tambahkan 'forum'
            'title.id' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'description.id' => 'nullable|string',
            'description.en' => 'nullable|string',
            'order' => 'nullable|integer',

            // Validasi baru (hanya jika type == 'forum')
            'debate_topic.id' => 'nullable|required_if:type,forum|string',
            'debate_topic.en' => 'nullable|required_if:type,forum|string',
            'debate_rules' => 'nullable|required_if:type,forum|string',
            'debate_start_time' => 'nullable|required_if:type,forum|date',
            'debate_end_time' => 'nullable|required_if:type,forum|date|after:debate_start_time',
            'phase1_end_time' => 'nullable|date|after:debate_start_time|before:debate_end_time',
            'phase2_end_time' => 'nullable|date|after:phase1_end_time|before:debate_end_time',
        ]);

        try {
            // Ambil semua data request
            $data = $request->all();

            // [PERBAIKAN KEAMANAN]
            // Bersihkan input HTML untuk 'debate_rules' jika ada
            if ($request->type == 'forum' && $request->has('debate_rules')) {
                // Pastikan Anda sudah menginstal mews/purifier
                $data['debate_rules'] = clean($request->debate_rules);
            }

            // Buat sub modul
            SubModule::create($data);

            return redirect()->back()->with('success', 'Sub modul berhasil ditambahkan!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan sub modul: ' . $e->getMessage());
        }
    }

    public function show(SubModule $subModul)
    {
        View::share('activeModulId', $subModul->modul_id);

        if ($subModul->type == 'reflection') {
            $subModul->load(['reflectionQuestions' => fn($q) => $q->orderBy('order', 'asc')]);
            return view('submodul.show_reflection', compact('subModul'));

        } elseif ($subModul->type == 'practicum') {
            $subModul->load([
                'learningMaterials' => fn($q) => $q->orderBy('order', 'asc'),
                'practicumUploadSlots' => fn($q) => $q->orderBy('order', 'asc')
            ]);
            return view('submodul.show_practicum', compact('subModul'));

        } elseif ($subModul->type == 'forum') {
            // [BLOK BARU] Tipe: Forum Debat
            // Kita muat relasi anggota tim
            // $subModul->load(['teamMembers']);

            // (Opsional) Ambil daftar siswa di kelas yang terkait dengan modul ini
            // Ini akan dibutuhkan untuk manajemen tim
            // $students = $subModul->module->kelas->flatMap->peserta->unique('id');
            // Ganti ini dengan logika yang sesuai untuk mendapatkan siswa yang relevan

            // Arahkan ke view baru: show_forum.blade.php
            return view('submodul.show_forum', compact('subModul')); // Tambahkan 'students' jika perlu
        }

        // Default (learning)
        $subModul->load(['learningMaterials' => fn($q) => $q->orderBy('order', 'asc')]);
        return view('submodul.show_learning', compact('subModul'));
    }
    /**
     * Mengambil data satu sub-modul sebagai JSON.
     * Ini digunakan untuk mengisi modal edit secara dinamis.
     *
     * @param  \App\Models\SubModule  $subModul
     * @return \Illuminate\Http\JsonResponse
     */
    public function showJson(SubModule $subModul)
    {
        // Kirim data sebagai JSON
        return response()->json([
            'id' => $subModul->id,
            'module_id' => $subModul->module_id,
            // getTranslations() akan mengembalikan array: {"id": "...", "en": "..."}
            'title' => $subModul->getTranslations('title'),
            'description' => $subModul->getTranslations('description'),
            'order' => $subModul->order,
        ]);
    }

    /**
     * Update data sub-modul yang ada dari modal edit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubModule  $subModul
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, SubModule $subModul)
    {
        // Validasi sama seperti store
        $request->validate([
            'title.id' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'description.id' => 'nullable|string',
            'description.en' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        try {
            // Model akan menangani pembaruan data JSON secara otomatis
            $subModul->update($request->all());

            return redirect()->back()->with('success', 'Sub modul berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui sub modul: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus sub-modul.
     *
     * @param  \App\Models\SubModule  $subModul
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SubModule $subModul)
    {
        try {
            $subModul->delete();
            return redirect()->back()->with('success', 'Sub modul berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus sub modul: ' . $e->getMessage());
        }
    }
}
