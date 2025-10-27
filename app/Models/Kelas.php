<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Kelas extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'kelas';
    public $translatable = ['nama_kelas'];

    protected $fillable = [
        'modul_id',
        'nama_kelas',
        'owner', // <-- KITA ASUMSIKAN INI ADALAH 'guru_id'
        'kode_join',
    ];

    // Relasi ke Modul
    public function modul()
    {
        return $this->belongsTo(Modul::class, 'modul_id');
    }

    /**
     * [DIPERBAIKI] Relasi ke Guru PEMILIK (Owner).
     * Menggunakan kolom 'owner'
     */
    public function guru()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    /**
     * [DIPERBAIKI] Relasi ke Peserta (HANYA SISWA).
     * Relasi 'hasManyThrough' yang memfilter hanya untuk siswa.
     * Ini akan digunakan untuk 'withCount('peserta')'.
     */
    public function peserta()
    {
        return $this->hasManyThrough(
            User::class,
            KelasUser::class,
            'kelas_id', // Foreign key di 'kelas_users'
            'id',       // Foreign key di 'users'
            'id',       // Local key di 'kelas'
            'user_id'   // Local key di 'kelas_users'
        )->whereDoesntHave('roles', fn($q) => $q->whereIn('name', ['Guru', 'Administrator']));
    }

    /**
     * [TETAP ADA] Relasi 'students' Anda yang lama.
     * Ini digunakan oleh Gradebook untuk 'load('students')'.
     * Ini mengambil SEMUA user, termasuk guru.
     */
    public function students()
    {
        return $this->hasManyThrough(
            User::class,
            KelasUser::class,
            'kelas_id',
            'id',
            'id',
            'user_id'
        );
    }

    /**
     * [BARU] Relasi untuk GURU TAMBAHAN (selain owner).
     * Ini digunakan untuk memfilter daftar kelas.
     */
    public function additional_teachers()
    {
        return $this->hasManyThrough(
            User::class,
            KelasUser::class,
            'kelas_id', // Foreign key di 'kelas_users'
            'id',       // Foreign key di 'users'
            'id',       // Local key di 'kelas'
            'user_id'   // Local key di 'kelas_users'
        )->whereHas('roles', fn($q) => $q->where('name', 'Guru'));
    }

    // --- Sisa Relasi (Tidak Berubah) ---

    public function subModuleProgress(): HasMany
    {
        return $this->hasMany(SubModuleProgress::class, 'kelas_id');
    }

    public function forumTeams()
    {
        return $this->hasMany(ForumTeam::class, 'kelas_id');
    }

    public function forumPosts()
    {
        return $this->hasMany(ForumTeam::class, 'kelas_id');
    }
}
