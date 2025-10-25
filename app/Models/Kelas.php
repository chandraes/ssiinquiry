<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Kelas extends Model
{
    use HasFactory;
    use HasTranslations; // <--- 2. GUNAKAN TRAIT

    protected $table = 'kelas';

    // 3. TENTUKAN KOLOM YANG BISA DITERJEMAHKAN
    public $translatable = ['nama_kelas'];

    protected $fillable = [
        'modul_id',
        'nama_kelas',
        'owner',
        'kode_join',
    ];

    // Relasi ke Modul
    public function modul()
    {
        return $this->belongsTo(Modul::class, 'modul_id');
    }

    // Relasi ke Guru/User
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Relasi ke KelasUser (Peserta)
    public function peserta()
    {
        return $this->hasMany(KelasUser::class, 'kelas_id');
    }

    public function students()
    {
        return $this->hasManyThrough(
            User::class,      // Model tujuan akhir yang ingin kita dapatkan
            KelasUser::class, // Model perantara (tabel 'kelas_users')
            'kelas_id',     // Foreign key di tabel 'kelas_users' (menghubungkan ke Kelas)
            'id',           // Foreign key di tabel 'users' (menghubungkan ke KelasUser)
            'id',           // Local key di tabel 'kelas' (menghubungkan ke KelasUser)
            'user_id'       // Local key di tabel 'kelas_users' (menghubungkan ke User)
        );
    }

    public function subModuleProgress(): HasMany
    {
        return $this->hasMany(SubModuleProgress::class, 'kelas_id');
    }

    public function forumTeams()
    {
        return $this->hasMany(ForumTeam::class, 'kelas_id');
    }



}
