<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user=Auth::user();

        return view ('admin.profile.index', compact('user'));
    }

    public function edit()
    {
        $user=Auth::user();
        
        return view ('admin.profile.edit', compact('user'));
    }

    /**
     * Update profile user (foto + password)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'nullable',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Ubah nama
        $user->name = ucfirst(strtolower($request->name));

        // Upload foto
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }
            $path = $request->file('photo')->store('profile', 'public');
            $user->photo = $path;
        }

        // Ubah password jika diisi
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
            } else {
                return back()->with('error', 'Password lama tidak sesuai.');
            }
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function deletePhoto()
    {
        $user = Auth::user();
        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
            $user->photo = null;
            $user->save();
        }
        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}
