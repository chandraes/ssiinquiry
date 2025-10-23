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
        // Validasi dengan format Spatie (dot notation)
        $request->validate([
            'modul_id' => 'required|exists:moduls,id', // Pastikan tabel 'moduls'
            'title.id' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'description.id' => 'nullable|string',
            'description.en' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        try {
            // Model SubModule sudah $fillable dan memiliki trait HasTranslations,
            // jadi $request->all() akan menangani penyimpanan JSON secara otomatis.
            SubModule::create($request->all());

            return redirect()->back()->with('success', 'Sub modul berhasil ditambahkan!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman detail untuk satu sub-modul (menampilkan daftar materi).
     *
     * @param  \App\Models\SubModule  $subModul
     * @return \Illuminate\View\View
     */
    public function show(SubModule $subModul)
    {
        // 1. Bagikan ID Modul Induk (untuk sidebar tetap aktif)
        View::share('activeModulId', $subModul->module_id);

        // 2. Tentukan View berdasarkan Tipe
        if ($subModul->type == 'reflection') {

            // Tipe: Pertanyaan Refleksi
            $subModul->load(['reflectionQuestions' => function ($query) {
                $query->orderBy('order', 'asc');
            }]);

            // TODO: Muat juga jawaban siswa untuk kelas yang aktif

            return view('submodul.show_reflection', compact('subModul'));

        }

        // Default (atau 'learning')
        // Tipe: Materi Pembelajaran
        $subModul->load(['learningMaterials' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);

        // [PENTING] GANTI NAMA VIEW LAMA ANDA
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
