<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReflectionAnswer extends Model
{
    protected $fillable = ['reflection_question_id', 'student_id', 'course_class_id', 'answer_text'];

    public function question()
    {
        return $this->belongsTo(ReflectionQuestion::class, 'reflection_question_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function courseClass()
    {
        return $this->belongsTo(Kelas::class, 'course_class_id');
    }
}
