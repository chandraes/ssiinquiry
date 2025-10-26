<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubModuleProgress extends Model
{
    use HasFactory;

    /**
     * Tentukan nama tabel secara eksplisit karena nama model
     * tidak jamak dari 'student_sub_module_progress'.
     */
    protected $table = 'student_sub_module_progress';

    /**
     * Kolom yang boleh diisi.
     */
    protected $fillable = [
        'user_id',
        'sub_module_id',
        'kelas_id',
        'completed_at',
        'score',      // <-- TAMBAHKAN INI
        'feedback',
    ];

    /**
     * Ubah 'completed_at' menjadi objek Carbon (tanggal/waktu).
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Relasi: Progress ini milik siapa (Siswa).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Progress ini untuk Sub-Modul yang mana.
     */
    public function subModule()
    {
        return $this->belongsTo(SubModule::class, 'sub_module_id');
    }

    /**
     * Relasi: Progress ini di Kelas yang mana.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
