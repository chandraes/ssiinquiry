<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        $userLogin = auth()->user(); // ambil user yang sedang login

        // Ambil semua data role
        $roles = Role::all();

        // Query default
        $query = User::with('roles');

        // Cek role user login
        if ($userLogin->roles->contains('name', 'Guru')) {
            // Jika user login adalah guru, hanya tampilkan user dengan role murid
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'Murid');
            });
        }

        $data = $query->get();

        // dd($data, $roles, $userLogin);

        return view('users.index', compact('data', 'roles'));
    }



    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        // dd($request->all());
        try {
            // Cek apakah username sudah ada
            if (User::where('username', $request->username)->exists()) {
                return redirect()->back()->withErrors(['username' => 'Username sudah terdaftar.'])->withInput();
            }

            // Cek apakah email sudah ada
            if (User::where('email', $request->email)->exists()) {
                return redirect()->back()->withErrors(['email' => 'Email sudah terdaftar.'])->withInput();
            }

            // Simpan user baru
            $user = User::create([
                'name' => strtoupper($request->name), // ubah ke huruf besar
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Simpan ke tabel role_user
            $user->roles()->attach($request->role_id);

            return redirect()->back()->with('success', 'Data user berhasil disimpan.');
        } catch (\Exception $e) {
            // Log::error('Gagal menyimpan user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        // dd($request);

        try {
            $user = User::findOrFail($id);

            $user->update([
                'name' => strtoupper($request->name), // ubah ke huruf besar
                'username' => $request->username,
                'email' => $request->email,
            ]);

            // Update relasi role, gunakan sync agar role lama tergantikan
            $user->roles()->sync([$request->role_id]);

            

            return redirect()->back()->with('success', 'Data user berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log::error('Gagal memperbarui user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.')->withInput();
        }
    }


    /**
     * Remove the resource from storage.
     */
    public function destroy(User $peserta)
    {
        // $peserta = User::findOrFail($id);
        $peserta->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
