<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LearningMaterial extends Model
{
    use HasTranslations; // <--- 2. Gunakan Trait

    // 3. Tentukan kolom yang bisa diterjemahkan
    public $translatable = ['title', 'content'];

    protected $fillable = [
        'sub_module_id',
        'title',
        'type',
        'content',
        'order',
    ];

    /**
     * 4. Penting: Ubah kolom 'content' agar selalu dibaca/ditulis sebagai JSON
     */
    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Relasi: Materi ini milik SubModule mana.
     */
    public function subModule()
    {
        return $this->belongsTo(SubModule::class);
    }
}
