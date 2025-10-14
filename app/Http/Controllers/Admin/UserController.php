<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        $data = User::orderBy('id', 'desc')->get();
        return view('admin.users.index', compact('data'));
    }
    
    /**
     * Show the form for creating the resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return view('admin.users.create');
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
        ]);

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
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->back()->with('success', 'Data user berhasil disimpan.');
        } catch (\Exception $e) {
            // Log error agar bisa dilacak oleh developer
            Log::error('Gagal menyimpan user: '.$e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request, User $user)
{
    // 1. Validasi dengan Rule::unique untuk mengabaikan user saat ini
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => [
            'required',
            'string',
            'max:255',
            // PENTING: Mengecualikan ID user yang sedang diupdate
            // Rule::unique('users', 'username')->ignore($user->id),
        ],
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            // PENTING: Mengecualikan ID user yang sedang diupdate
            // Rule::unique('users', 'email')->ignore($user->id),
        ],
        // Password bersifat opsional/nullable
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    // dd($request->all(), $user->id);

    try {
        // 2. Data yang akan diperbarui (tanpa field 'posisi')
        $updateData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ];

        // 3. Cek jika password diisi, maka hash dan update
        if (!empty($request->password)) {
            $updateData['password'] = Hash::make($request->password);
        }

        // dd($updateData);

        // 4. Lakukan pembaruan data
        $user->update($updateData);

        // dd($user);
        // 5. Redirect Response
        // Ganti 'nama.route.kembali' dengan nama route list pengguna Anda, misalnya 'admin.user.index'
        return redirect()->route('admin.user.update')->with('success', 'Data pengguna ' . $user->name . ' berhasil diperbarui.');

    } catch (\Exception $e) {
        // Log error untuk debugging internal
        Log::error('Gagal memperbarui user (ID: '.$user->id.'): '.$e->getMessage());

        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data. Cek log.');
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
