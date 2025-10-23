<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;

use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Http\Request;

class LearningMaterialController extends Controller
{
    /**
     * Menyimpan Learning Material baru.
     */
    /**
     * Menyimpan Learning Material baru.
     * Logika disederhanakan: SEMUA TIPE sekarang hanya menyimpan URL.
     */
   /**
     * Menyimpan Learning Material baru.
     * [LOGIKA DIPERBARUI] Sekarang menangani 'rich_text' dan 'url'.
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'sub_module_id' => 'required|exists:sub_modules,id',
            // Tambahkan 'rich_text' sebagai tipe yang valid
            'type' => 'required|in:video,article,infographic,regulation,rich_text',
            'title.id' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',

            // Validasi URL (wajib JIKA tipenya BUKAN rich_text)
            'content_url' => 'nullable|required_unless:type,rich_text|url',

            // Validasi Rich Text (wajib HANYA JIKA tipenya rich_text)
            'content_rich_text.id' => 'nullable|required_if:type,rich_text|string',
            'content_rich_text.en' => 'nullable|required_if:type,rich_text|string',

            'order' => 'nullable|integer',
        ]);

        try {

            $contentPayload = null;
            $type = $request->type;

            // 2. Siapkan payload 'content' untuk Spatie
            if ($type == 'rich_text') {
                /**
                 * [PERBAIKAN KEAMANAN]
                 * Bersihkan setiap input HTML sebelum menyimpannya.
                 * clean() adalah helper dari mews/purifier.
                 */
                $clean_id = clean($request->content_rich_text['id']);
                $clean_en = clean($request->content_rich_text['en']);

                $contentPayload = [
                    'id' => $clean_id,
                    'en' => $clean_en
                ];

            } else {
                /**
                 * Jika tipenya URL, kita harus membuatnya translatable
                 * dengan menyimpan URL yang sama untuk kedua bahasa.
                 * Spatie akan menyimpan ini sebagai:
                 * {"id": {"url": "..."}, "en": {"url": "..."}}
                 */
                $url = $request->content_url;
                $contentPayload = [
                    'id' => ['url' => $url],
                    'en' => ['url' => $url]
                ];
            }

            // 3. Simpan ke Database
            LearningMaterial::create([
                'sub_module_id' => $request->sub_module_id,
                'title' => $request->title, // Spatie menangani ini
                'type' => $type,
                'content' => $contentPayload, // Spatie menangani ini
                'order' => $request->order ?? 0,
            ]);

            return redirect()->back()->with('success', 'Materi berhasil ditambahkan!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan materi: ' . $e->getMessage());
        }
    }

    public function edit(LearningMaterial $material)
    {
        $contentTranslations = $material->getTranslations('content');
        $url = null;

        if ($material->type != 'rich_text') {
            // Coba ambil URL dari format baru: {"id": {"url": "..."}}
            if (isset($contentTranslations['id']) && is_array($contentTranslations['id']) && isset($contentTranslations['id']['url'])) {
                $url = $contentTranslations['id']['url'];
            } else {
                // Coba ambil URL dari format LAMA: {"url": "..."}
                $rawContent = json_decode($material->getRawOriginal('content'), true);
                if (is_array($rawContent) && isset($rawContent['url'])) {
                    $url = $rawContent['url'];
                }
            }
        }

        return response()->json([
            'id' => $material->id,
            'type' => $material->type,
            'title' => $material->getTranslations('title'),
            'content' => $contentTranslations, // Untuk Rich Text
            'content_url' => $url // Untuk tipe URL
        ]);
    }

    /**
     * [BARU] Update data learning material.
     */
    public function update(Request $request, LearningMaterial $material)
    {
        // 1. Validasi
        $request->validate([
            'title.id' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',

            // Validasi kondisional (kita asumsikan TIPE tidak bisa diubah)
            'content_url' => 'nullable|required_unless:type,rich_text|url',
            'content_rich_text.id' => 'nullable|required_if:type,rich_text|string',
            'content_rich_text.en' => 'nullable|required_if:type,rich_text|string',

            'order' => 'nullable|integer',
        ]);

        try {

            $contentPayload = null;
            $type = $material->type; // Ambil tipe dari material yang ada

            // 2. Siapkan payload 'content'
            if ($type == 'rich_text') {
                // [PERBAIKAN KEAMANAN]
                $clean_id = clean($request->content_rich_text['id']);
                $clean_en = clean($request->content_rich_text['en']);

                $contentPayload = [
                    'id' => $clean_id,
                    'en' => $clean_en
                ];
            } else {
                $url = $request->content_url;
                $contentPayload = [
                    'id' => ['url' => $url],
                    'en' => ['url' => $url]
                ];
            }

            // 3. Update Database
            $material->update([
                'title' => $request->title,
                'content' => $contentPayload,
                'order' => $request->order ?? 0,
            ]);

            return redirect()->back()->with('success', 'Materi berhasil diperbarui!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui materi: ' . $e->getMessage());
        }
    }

    /**
     * [BARU] Menghapus learning material.
     */
    public function destroy(LearningMaterial $material)
    {
        try {
            // (Opsional) Hapus file dari storage jika Anda menyimpannya
            // if ($material->type == 'infographic' || $material->type == 'regulation') {
            //    $content = json_decode($material->getRawOriginal('content'), true);
            //    if (isset($content['path'])) {
            //        Storage::disk('public')->delete($content['path']);
            //    }
            // }

            $material->delete();
            return redirect()->back()->with('success', 'Materi berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus materi: ' . $e->getMessage());
        }
    }


}
