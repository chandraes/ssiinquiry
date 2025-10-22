<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

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

    public function kelas_user()
    {
        // Parameter: Model tujuan, Nama tabel pivot
        return $this->hasMany(KelasUser::class, 'kelas_id');
    }      
}
