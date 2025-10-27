<?php

namespace App\Http\Controllers;

use App\Models\ProfileUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

     public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => 'nullable|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nomor_hp' => 'nullable|string|max:20',
            'asal_sekolah' => 'nullable|string|max:150',
        ]);

        try {
            // ... (Logika update Anda sudah benar semua) ...
            $profile = ProfileUser::firstOrNew(['user_id' => $user->id]);

            if ($request->hasFile('foto')) {
                if ($profile->foto && Storage::disk('public')->exists($profile->foto)) {
                    Storage::disk('public')->delete($profile->foto);
                }
                $file = $request->file('foto');
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::slug($user->name) . '_' . date('Ymd_His') . '.' . $extension;
                $path = $file->storeAs('foto', $fileName, 'public');
                $profile->foto = $path;
            }

            $profile->nomor_hp = $request->nomor_hp;
            $profile->asal_sekolah = $request->asal_sekolah;
            $profile->user_id = $user->id;
            $profile->save();

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
                $user->save();
            }

            // PERUBAHAN DI SINI: Cek jika ini request AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui!'
                ]);
            }

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete_foto()
    {
        $user = Auth::user();
        $profile = ProfileUser::where('user_id', $user->id)->first();

        if ($profile && $profile->foto) {
            // Hapus file dari storage
            Storage::disk('public')->delete($profile->foto);
            $profile->foto = null;
            $profile->save();
        }

        return redirect()->back()->with('success', 'Foto profil berhasil dihapus!');
    }


}
