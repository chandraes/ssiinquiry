<?php

namespace App\Http\Controllers\Murid;

use App\Models\ProfileUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        return view('murid.profile.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfileUser $profileUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfileUser $profileUser)
    {
        $user = Auth::user();
        return view('murid.profile.edit', compact('user'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfileUser $profileUser)
    {
        //
    }
}
