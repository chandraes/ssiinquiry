<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticumSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'practicum_upload_slot_id',
        'student_id',
        'course_class_id',
        'file_path',
        'original_filename',
    ];

    // Relasi: Unggahan ini milik Slot mana
    public function slot()
    {
        return $this->belongsTo(PracticumUploadSlot::class, 'practicum_upload_slot_id');
    }

    // Relasi: Unggahan ini milik Siswa (User) mana
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relasi: Unggahan ini dibuat di Kelas mana
    public function courseClass()
    {
        return $this->belongsTo(Kelas::class, 'course_class_id');
    }

    public function linkedForumPosts()
    {
        return $this->belongsToMany(ForumPost::class, 'forum_post_evidence');
    }

    public function evidencePosts()
{
    return $this->belongsToMany(
        ForumPost::class,
        'forum_post_evidence',
        'practicum_submission_id',
        'forum_post_id'
    );
}
}
