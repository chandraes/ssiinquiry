<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
