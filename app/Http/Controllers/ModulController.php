<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Modul;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userLogin = auth()->user();
        $roles = Role::all();

        // Query dasar
        $query = Modul::with('users.roles');

        // Jika user login adalah Guru
        if ($userLogin->roles->contains('name', 'Guru')) {
            // Tampilkan semua modul, tapi nanti di Blade dicek apakah dia owner atau bukan
            // Jika kamu hanya ingin tampilkan modul milik dia saja, pakai whereHas di bawah ini:
            // $query->whereHas('users', fn($q) => $q->where('user_id', $userLogin->id));
        }

        // Admin melihat semua modul
        elseif ($userLogin->roles->contains('name', 'Administrator')) {
            // Tidak perlu filter
        }

        // dd($userLogin);

        $data = $query->get();

        return view('modul.index', compact('data', 'roles', 'userLogin'));
    }



    public function search(Request $request)
    {
        $userLogin = auth()->user(); // ambil user login
        $search = $request->q;

        // Query dasar pencarian user
        $query = User::where('name', 'like', "%{$search}%")
                    ->select('id', 'name')
                    ->limit(10);

        // 🔒 Batasi hasil berdasarkan role user yang login
        if ($userLogin->roles->contains('name', 'Guru')) {
            // Guru hanya bisa melihat Guru
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'Guru');
            });
        } elseif ($userLogin->roles->contains('name', 'Administrator')) {
            // Admin bisa melihat Guru dan Admin
            $query->whereHas('roles', function ($q) {
                $q->whereIn('name', ['Guru', 'Administrator']);
            });
        } else {
            // Role lain tidak boleh melihat siapa pun
            $query->whereRaw('1 = 0'); // selalu false → hasil kosong
        }

        $users = $query->get();

        return response()->json($users);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_id' => 'required|string|max:255',
            'judul_en' => 'required|string|max:255',
            'deskripsi_id' => 'nullable|string',
            'deskripsi_en' => 'nullable|string',
            'owner' => 'required|array', // multiple owner
        ]);

        // dd($request->all());

        try {
            $modul = Modul::create([
                'judul_id' => $request->judul_id,
                'judul_en' => $request->judul_en,
                'deskripsi_id' => $request->deskripsi_id,
                'deskripsi_en' => $request->deskripsi_en,
            ]);

            // Simpan relasi ke tabel pivot modul_user
            $modul->owners()->attach($request->owner);

            // dd($modul);
            return redirect()->back()->with('success', 'Data modul berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_id'      => 'required|string|max:255',
            'judul_en'      => 'required|string|max:255',
            'deskripsi_id'  => 'nullable|string',
            'deskripsi_en'  => 'nullable|string',
            'owner'         => 'nullable|array',  // multiple owner
            'owner.*'       => 'exists:users,id',
        ]);

        try {
            $modul = Modul::with('users')->findOrFail($id);
            $userLogin = auth()->user();

            // Cek apakah user login adalah owner atau admin
            $isOwner = $modul->users->contains('id', $userLogin->id) || $userLogin->roles->contains('name', 'Administrator');

            if (!$isOwner) {
                return redirect()->back()->with('error', 'Kamu tidak memiliki izin untuk mengubah data modul ini.');
            }

            // Update data modul
            $modul->update([
                'judul_id'      => $request->judul_id,
                'judul_en'      => $request->judul_en,
                'deskripsi_id'  => $request->deskripsi_id,
                'deskripsi_en'  => $request->deskripsi_en,
            ]);

            // Sinkronisasi owner di tabel pivot (jika diubah)
            if ($request->has('owner')) {
                $modul->users()->sync($request->owner);
            }

            return redirect()->back()->with('success', 'Data modul berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $modul = Modul::with('users')->findOrFail($id);
            $userLogin = auth()->user();

            // Cek apakah user login adalah owner atau admin
            $isOwner = $modul->users->contains('id', $userLogin->id) || $userLogin->roles->contains('name', 'Administrator');

            if (!$isOwner) {
                return redirect()->back()->with('error', 'Kamu tidak memiliki izin untuk menghapus data modul ini.');
            }

            $modul->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


}
