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
            $profile = ProfileUser::firstOrNew(['user_id' => $user->id]);

            // Upload foto baru
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($profile->foto && Storage::disk('public')->exists($profile->foto)) {
                    Storage::disk('public')->delete($profile->foto);
                }

                $file = $request->file('foto');
                $extension = $file->getClientOriginalExtension();

                // Nama file menggunakan nama user
                $fileName = Str::slug($user->name) . '_' . date('Ymd_His') . '.' . $extension;

                // Simpan ke folder foto di disk 'public'
                $path = $file->storeAs('foto', $fileName, 'public');

                // Simpan path ke database
                $profile->foto = $path;
            }

            // Update data profil
            $profile->nomor_hp = $request->nomor_hp;
            $profile->asal_sekolah = $request->asal_sekolah;
            $profile->user_id = $user->id;
            $profile->save();

            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
                $user->save();
            }

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
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
