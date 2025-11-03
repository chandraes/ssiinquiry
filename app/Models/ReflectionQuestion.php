<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReflectionQuestion extends Model
{
    use HasFactory;
    use HasTranslations;
    public $translatable = ['question_text'];
    protected $fillable = ['sub_module_id', 'question_text', 'order', 'type'];

    public function subModule()
    {
        return $this->belongsTo(SubModule::class);
    }

    public function answers()
    {
        return $this->hasMany(ReflectionAnswer::class);
    }

    /**
     * [BARU] Relasi ke pilihan ganda (options).
     * Satu pertanyaan punya banyak pilihan.
     */
    public function options()
    {
        return $this->hasMany(ReflectionQuestionOption::class, 'reflection_question_id');
    }

    /**
     * [BARU] Helper attribute untuk mengambil kunci jawaban
     * Ini akan sangat berguna nanti.
     */
    public function getCorrectAnswerAttribute()
    {
        // Cari di relasi 'options' mana yang 'is_correct' == true
        return $this->options()->where('is_correct', true)->first();
    }
}
