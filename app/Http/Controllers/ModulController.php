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
        $userLogin = auth()->user(); // ambil user yang sedang login

        // Ambil semua role
        $roles = Role::all();

        // Query dasar modul
        $query = Modul::with('user', 'user.roles');

        // Jika user login memiliki role Guru
        if ($userLogin->roles->contains('name', 'Guru')) {
            // Hanya tampilkan modul milik sendiri
            $query->where('owner', $userLogin->id);
        }

        // Jika user login adalah Admin, tampilkan semua
        elseif ($userLogin->roles->contains('name', 'Admin')) {
            // Tidak ada filter owner, tampilkan semua modul
        }

        // Ambil data modul
        $data = $query->get();

        return view('modul.index', compact('data', 'roles'));
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
        // Validasi input
        $request->validate([
            'judul_id' => 'required|string|max:255',
            'judul_en' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            // Simpan data modul
            $modul = Modul::create([
                'judul_id' => $request->judul_id,
                'judul_en' => $request->judul_en,
                'deskripsi'      => $request->deskripsi,
                'owner'          => auth()->user()->id,
            ]);

            // Jika berhasil, tampilkan notifikasi sukses
            return redirect()->back()->with('success', 'Data modul berhasil dibuat!');
        } catch (\Exception $e) {
            // Jika gagal, tampilkan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Modul $modul)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modul $modul)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'judul_id' => 'required|string|max:255',
            'judul_en' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $modul = Modul::findOrFail($id);

            $modul->update([
                'judul_id' => $request->judul_id,
                'judul_en' => $request->judul_en,
                'deskripsi' => $request->deskripsi,
            ]);

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
            $modul = Modul::findOrFail($id);
            $modul->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
