<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ReflectionQuestionOption extends Model
{
    use HasFactory;
    use HasTranslations; // <-- [TAMBAHKAN INI]

    /**
     * [BARU] Tentukan kolom mana yang bisa diterjemahkan.
     */
    public $translatable = ['option_text'];

    /**
     * Tentukan nama tabel jika berbeda dari 'reflection_question_options'
     * (Dalam kasus ini, namanya sudah sesuai standar Laravel, jadi ini opsional)
     */
    protected $table = 'reflection_question_options';

    /**
     * Field yang boleh diisi.
     */
    protected $fillable = [
        'reflection_question_id',
        'option_text',
        'is_correct',
    ];

    /**
     * Tipe data cast.
     * Kita pastikan 'is_correct' selalu boolean.
     */
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Relasi balik: Pilihan ini milik Pertanyaan (Question) mana.
     */
    public function question()
    {
        return $this->belongsTo(ReflectionQuestion::class, 'reflection_question_id');
    }
}
