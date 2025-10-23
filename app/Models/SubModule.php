<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SubModule extends Model
{
    use HasTranslations; // <--- 2. Gunakan Trait

    // 3. Tentukan kolom mana yang bisa diterjemahkan
    public $translatable = ['title', 'description'];

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        'modul_id',
        'type',
        'title',
        'description',
        'order',
    ];

    /**
     * Relasi: SubModule ini milik Module mana.
     */
    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }

    /**
     * Relasi: SubModule ini punya banyak materi pembelajaran.
     */
    public function learningMaterials()
    {
        return $this->hasMany(LearningMaterial::class)->orderBy('order');
    }

    /**
     * Relasi: SubModule ini punya banyak pertanyaan refleksi.
     */
    public function reflectionQuestions()
    {
        return $this->hasMany(ReflectionQuestion::class)->orderBy('order');
    }

    public function practicumUploadSlots()
    {
        return $this->hasMany(PracticumUploadSlot::class)->orderBy('order');
    }
}
