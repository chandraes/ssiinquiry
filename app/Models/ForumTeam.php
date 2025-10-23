<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumTeam extends Model
{
    use HasFactory;

    // Tentukan nama tabelnya secara eksplisit
    protected $table = 'forum_teams';

    protected $fillable = [
        'sub_module_id',
        'kelas_id',
        'user_id',
        'team',
    ];

    // Relasi ke SubModul
    public function subModule()
    {
        return $this->belongsTo(SubModule::class);
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke User (Siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
