<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasUser extends Model
{
    use HasFactory;

    protected $table = 'kelas_users';

    protected $fillable = [
        'kelas_id',
        'user_id',
        'pro_kontra_id',
    ];

    /**
     * Relasi ke Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function modul()
    {
        return $this->belongsTo(Modul::class, 'modul_id');
    }

    /**
     * Relasi ke SEMUA pengguna di kelas (termasuk siswa).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'kelas_users', 'kelas_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi HANYA ke SISWA (pengguna yang BUKAN guru).
     * Ini yang digunakan untuk 'withCount('peserta')'.
     */
    public function peserta()
    {
        return $this->belongsToMany(User::class, 'kelas_users', 'kelas_id', 'user_id')
                    ->whereDoesntHave('roles', fn($q) => $q->whereIn('name', ['Guru', 'Administrator']));
    }

    /**
     * [PERBAIKAN] Relasi ke GURU.
     * Ini mengambil SATU user dari pivot yang memiliki role 'Guru'.
     */
    public function guru()
    {
        // hasOneThrough atau morphOne mungkin lebih bersih,
        // tapi ini adalah cara paling eksplisit menggunakan belongsToMany
        return $this->belongsToMany(User::class, 'kelas_users', 'kelas_id', 'user_id')
                    ->whereHas('roles', fn($q) => $q->where('name', 'Guru'))
                    ->limit(1); // Asumsi 1 kelas 1 guru
    }

    /**
     * Helper untuk menampilkan label pro/kontra
     */
    public function getProKontraLabelAttribute()
    {
        return match($this->pro_kontra_id) {
            '1' => 'Pro',
            '2' => 'Kontra',
            default => '-',
        };
    }


}
