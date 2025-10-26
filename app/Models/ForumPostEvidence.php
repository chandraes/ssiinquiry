<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPostEvidence extends Model
{
    use HasFactory;

    protected $table = 'forum_post_evidence';

    protected $fillable = [
        'forum_post_id',
        'practicum_submission_id',
    ];

    // Relasi ke postingan forum
    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'forum_post_id');
    }

    // Relasi ke file submission praktikum
    public function submission()
    {
        return $this->belongsTo(PracticumSubmission::class, 'practicum_submission_id');
    }
}
