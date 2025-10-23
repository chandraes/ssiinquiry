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
        'visibility',
        'team',
        'content',
        'parent_post_id'
    ];

    // Relasi ke SubModul induk
    public function subModule()
    {
        return $this->belongsTo(SubModule::class);
    }

    // Relasi ke User (pembuat postingan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Postingan Induk (jika ini balasan)
    public function parentPost()
    {
        return $this->belongsTo(ForumPost::class, 'parent_post_id');
    }

    // Relasi ke Balasan (jika ini postingan utama)
    public function replies()
    {
        return $this->hasMany(ForumPost::class, 'parent_post_id')->latest(); // Urutkan balasan terbaru di atas
    }

    // Relasi untuk bukti data Phyphox (akan kita buat tabel pivotnya)
    public function evidence()
    {
        return $this->belongsToMany(PracticumSubmission::class, 'forum_post_evidence');
    }
}
