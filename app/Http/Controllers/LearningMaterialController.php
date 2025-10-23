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
                 * Jika tipenya rich_text, Spatie mengharapkan data 'content'
                 * dalam format {"id": "HTML...", "en": "HTML..."}
                 * Ini sudah dikirim oleh form 'content_rich_text'
                 */
                $contentPayload = $request->content_rich_text;

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


}
