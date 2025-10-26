<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_module_id',
        'user_id',
        'kelas_id', // <-- [BARU] Tambahkan ini
        'visibility',
        'team',
        'content',
        'parent_post_id'
    ];

    // [BARU] Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi ke SubModul induk
    public function subModule()
    {
        return $this->belongsTo(SubModule::class, 'sub_module_id');
    }

    // Relasi ke User (pembuat postingan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Postingan Induk (jika ini balasan)
    public function parentPost()
    {
        return $this->belongsTo(ForumPost::class, 'parent_post_id');
    }

    // Relasi ke Balasan (jika ini postingan utama)
    public function replies()
    {
        return $this->hasMany(ForumPost::class, 'parent_post_id')->latest();
    }

    // Relasi untuk bukti data Phyphox (via tabel forum_post_evidence)
    public function evidence()
    {
        return $this->belongsToMany(
            PracticumSubmission::class,
            'forum_post_evidence',       // Nama tabel pivot
            'forum_post_id',             // Foreign key di pivot yg ke model ini
            'practicum_submission_id'    // Foreign key di pivot yg ke model tujuan
        );
    }
}
