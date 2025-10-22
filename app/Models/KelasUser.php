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

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
