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
        'guru_id',
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
