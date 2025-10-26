<?php

namespace App\Http\Controllers;

use App\Models\SubModule;
use App\Models\LearningMaterial; // Kita akan butuh ini nanti
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\View;
use Mews\Purifier\Facades\Purifier;
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
        // Validasi HANYA untuk field yang ada di modal
        $request->validate([
            'modul_id' => 'required|exists:moduls,id',
            'type' => 'required|in:learning,reflection,practicum,forum',
            'title.id' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'description.id' => 'nullable|string',
            'description.en' => 'nullable|string',
            'order' => 'nullable|integer',
            'max_points' => 'nullable|integer|min:0',
            // SEMUA validasi 'debate_*' dihapus
        ]);

        try {
            $data = $request->all();

            if ($data['type'] == 'learning') {
                $data['max_points'] = 0;
            }

            // Logika 'clean()' untuk 'debate_rules' dihapus

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

   public function showJson(SubModule $subModul)
    {
        return response()->json([
            'id' => $subModul->id,

            // Data Inti
            'type' => $subModul->type,
            'order' => $subModul->order,
            'max_points' => $subModul->max_points,

            // Translatable fields
            'title' => $subModul->getTranslations('title'),
            'description' => $subModul->getTranslations('description'),

            // SEMUA field 'debate_*' dihapus dari sini
        ]);
    }

    /**
     * Memperbarui sub-modul yang ada di database.
     * [DISESUAIKAN agar konsisten dengan 'store']
     */
    public function update(Request $request, SubModule $subModul)
    {
        // Validasi HANYA untuk field yang ada di modal
        $request->validate([
            'type' => 'required|in:learning,reflection,practicum,forum',
            'title.id' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'description.id' => 'nullable|string',
            'description.en' => 'nullable|string',
            'order' => 'nullable|integer',
            'max_points' => 'nullable|integer|min:0',
            // SEMUA validasi 'debate_*' dihapus
        ]);

        try {
            $data = $request->all();

            if ($data['type'] == 'learning') {
                $data['max_points'] = 0;
            }

            // Logika 'clean()' untuk 'debate_rules' dihapus

            $subModul->update($data);

            return redirect()->back()->with('success', 'Sub modul berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui sub modul: '. $e->getMessage());
        }
    }

    /**
     * [DIPERBARUI] Menyimpan pengaturan spesifik untuk sub-modul Tipe Forum.
     * Sekarang menangani 'debate_rules' sebagai field translatable.
     */
    public function updateForumSettings(Request $request, SubModule $subModul)
    {
        if ($subModul->type !== 'forum') {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        // 1. [DIUBAH] Validasi untuk 'debate_rules' sebagai array
        $request->validate([
            'debate_topic.id' => 'required|string|max:255',
            'debate_topic.en' => 'required|string|max:255',

            'debate_rules' => 'required|array', // Harus array
            'debate_rules.id' => 'required|string', // Wajib ID
            'debate_rules.en' => 'required|string', // Wajib EN

            'debate_start_time' => 'required|date',
            'debate_end_time' => 'required|date|after:debate_start_time',
            'phase1_end_time' => 'nullable|date|after:debate_start_time|before:debate_end_time',
            'phase2_end_time' => 'nullable|date|after:phase1_end_time|before:debate_end_time',
        ]);

        try {
            // 2. [DIUBAH] Ambil data dan bersihkan HTML untuk array
            $data = $request->except('debate_rules'); // Ambil semua KECUALI rules

            // Bersihkan setiap elemen 'debate_rules' secara manual
           $cleanedRules = [
                'id' => Purifier::clean($request->input('debate_rules.id')),
                'en' => Purifier::clean($request->input('debate_rules.en'))
            ];

            $data['debate_rules'] = $cleanedRules; // Masukkan kembali data rules yang sudah bersih

            // 3. Update sub-modul
            $subModul->update($data);

            return redirect()->back()->with('success', 'Pengaturan forum berhasil diperbarui!');

        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengaturan: '. $e->getMessage());
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

    public function show_siswa(SubModule $subModul)
    {
        // 1. Bagikan ID Modul Induk (untuk sidebar tetap aktif)
        View::share('activeModulId', $subModul->modul_id);

        // 2. Tentukan View berdasarkan Tipe
       if ($subModul->type == 'reflection') {
            $subModul->load(['reflectionQuestions' => fn($q) => $q->orderBy('order', 'asc'),
             'reflectionQuestions.answers']);

            // TODO: Muat juga jawaban siswa untuk kelas yang aktif

            // dd($subModul);
            return view('submodul.siswa.show_reflection', compact('subModul'));

        } elseif ($subModul->type == 'practicum') {
            $subModul->load([
                'learningMaterials' => fn($q) => $q->orderBy('order', 'asc'),
                'practicumUploadSlots' => fn($q) => $q->orderBy('order', 'asc'),
                'practicumUploadSlots.submissions'
            ]);
            return view('submodul.siswa.show_practicum', compact('subModul'));

        } elseif ($subModul->type == 'forum') {
            // [BLOK BARU] Tipe: Forum Debat
            // Kita muat relasi anggota tim
            // $subModul->load(['teamMembers']);

            // (Opsional) Ambil daftar siswa di kelas yang terkait dengan modul ini
            // Ini akan dibutuhkan untuk manajemen tim
            // $students = $subModul->module->kelas->flatMap->peserta->unique('id');
            // Ganti ini dengan logika yang sesuai untuk mendapatkan siswa yang relevan

            // Arahkan ke view baru: show_forum.blade.php
            return view('submodul.siswa.show_forum', compact('subModul')); // Tambahkan 'students' jika perlu
        }

        // Default (learning)
        $subModul->load(['learningMaterials' => fn($q) => $q->orderBy('order', 'asc')]);
        return view('submodul.siswa.show_learning', compact('subModul'));
    }
}
