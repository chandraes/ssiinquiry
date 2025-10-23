<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ReflectionQuestion extends Model
{
    use HasTranslations; // <--- 2. Gunakan Trait

    // 3. Tentukan kolom yang bisa diterjemahkan
    public $translatable = ['question_text'];

    protected $fillable = [
        'sub_module_id',
        'question_text',
        'order',
    ];

    /**
     * Relasi: Pertanyaan ini milik SubModule mana.
     */
    public function subModule()
    {
        return $this->belongsTo(SubModule::class);
    }

    /**
     * Relasi: Pertanyaan ini punya banyak jawaban.
     */
    public function reflectionAnswer()
    {
        return $this->hasMany(ReflectionAnswer::class);
    }
}
