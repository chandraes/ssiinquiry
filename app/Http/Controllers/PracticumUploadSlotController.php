<?php

namespace App\Http\Controllers;

use App\Models\PracticumUploadSlot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class PracticumUploadSlotController extends Controller
{
    /**
     * Menyimpan slot unggahan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sub_module_id' => 'required|exists:sub_modules,id',
            'label.id' => 'required|string|max:255',
            'label.en' => 'required|string|max:255',
            'description.id' => 'nullable|string',
            'description.en' => 'nullable|string',
            'experiment_group' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        try {
            PracticumUploadSlot::create($request->all());
            return redirect()->back()->with('success', 'Slot unggahan berhasil ditambahkan!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan slot: ' . $e->getMessage());
        }
    }

    /**
     * Mengambil data slot sebagai JSON untuk modal edit.
     */
    public function edit(PracticumUploadSlot $slot)
    {
        return response()->json([
            'id' => $slot->id,
            'label' => $slot->getTranslations('label'),
            'description' => $slot->getTranslations('description'),
            'experiment_group' => $slot->experiment_group,
            'order' => $slot->order,
        ]);
    }

    /**
     * Update data slot unggahan.
     */
    public function update(Request $request, PracticumUploadSlot $slot)
    {
        $request->validate([
            'label.id' => 'required|string|max:255',
            'label.en' => 'required|string|max:255',
            'description.id' => 'nullable|string',
            'description.en' => 'nullable|string',
            'experiment_group' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        try {
            $slot->update($request->all());
            return redirect()->back()->with('success', 'Slot unggahan berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui slot: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus slot unggahan.
     */
    public function destroy(PracticumUploadSlot $slot)
    {
        try {
            $slot->delete();
            return redirect()->back()->with('success', 'Slot unggahan berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus slot: ' . $e->getMessage());
        }
    }
}
