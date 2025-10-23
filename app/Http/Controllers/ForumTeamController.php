<?php

namespace App\Http\Controllers;

use App\Models\ForumTeam;
use App\Models\Kelas;
use App\Models\SubModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ForumTeamController extends Controller
{
    /**
     * Menampilkan halaman manajemen tim untuk 1 forum & 1 kelas.
     */
   public function index(Kelas $kelas, SubModule $subModule)
    {
        // [PERBAIKAN DI SINI]
        // Ganti 'peserta()' (yang mengembalikan KelasUser)
        // dengan 'students()' (yang mengembalikan User)
        $allStudentsInClass = $kelas->students()->get();

        // 2. Ambil ID siswa yang sudah punya tim di forum & kelas ini
        $assignedMembers = ForumTeam::where('sub_module_id', $subModule->id)
                                ->where('kelas_id', $kelas->id)
                                ->get();

        $proTeamIds = $assignedMembers->where('team', 'pro')->pluck('user_id');
        $conTeamIds = $assignedMembers->where('team', 'con')->pluck('user_id');

        // 3. Pisahkan siswa menjadi 3 grup
        // Kode di bawah ini sekarang akan berfungsi dengan benar
        $proTeam = $allStudentsInClass->whereIn('id', $proTeamIds);
        $conTeam = $allStudentsInClass->whereIn('id', $conTeamIds);

        $assignedUserIds = $proTeamIds->merge($conTeamIds);
        $unassignedStudents = $allStudentsInClass->whereNotIn('id', $assignedUserIds);

        return view('forum.team_management', compact(
            'kelas',
            'subModule',
            'unassignedStudents',
            'proTeam',
            'conTeam'
        ));
    }

    public function assignTeam(Request $request)
    {
        $request->validate([
            'sub_module_id' => 'required|exists:sub_modules,id',
            'kelas_id' => 'required|exists:kelas,id',
            'user_id' => 'required|exists:users,id',
            'team' => 'required|in:pro,con', // Hanya 'pro' atau 'con'
        ]);

        try {
            // Gunakan updateOrCreate:
            // 1. Cari berdasarkan 3 key ini.
            // 2. Jika ketemu, update 'team'-nya.
            // 3. Jika tidak ketemu, buat record baru.
            ForumTeam::updateOrCreate(
                [
                    'sub_module_id' => $request->sub_module_id,
                    'kelas_id' => $request->kelas_id,
                    'user_id' => $request->user_id,
                ],
                [
                    'team' => $request->team
                ]
            );

            return response()->json(['success' => true, 'message' => 'Tim berhasil diperbarui.']);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * [BARU] Menghapus seorang siswa dari tim (mengembalikan ke 'unassigned').
     */
    public function removeTeam(Request $request)
    {
        $request->validate([
            'sub_module_id' => 'required|exists:sub_modules,id',
            'kelas_id' => 'required|exists:kelas,id',
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            ForumTeam::where('sub_module_id', $request->sub_module_id)
                ->where('kelas_id', $request->kelas_id)
                ->where('user_id', $request->user_id)
                ->delete();

            return response()->json(['success' => true, 'message' => 'Siswa dihapus dari tim.']);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
