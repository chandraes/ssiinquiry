<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReflectionAnswer extends Model
{
    protected $fillable = [
        'reflection_question_id',
        'student_id',
        'course_class_id',
        'answer_text',
    ];

    /**
     * Relasi: Jawaban ini milik Pertanyaan mana.
     */
    public function question()
    {
        return $this->belongsTo(ReflectionQuestion::class, 'reflection_question_id');
    }

    /**
     * Relasi: Jawaban ini milik Murid (User) mana.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Jawaban ini dibuat di Kelas mana.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
