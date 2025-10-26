<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PracticumUploadSlot extends Model
{
    use HasFactory, HasTranslations;

    // Tentukan kolom yang bisa diterjemahkan (multi-bahasa)
    public $translatable = ['label', 'description'];

    protected $fillable = [
        'sub_module_id',
        'label',
        'description',
        'experiment_group',
        'order',
        'phyphox_experiment_type',
    ];

    // Relasi: Slot ini milik SubModul mana
    public function subModule()
    {
        return $this->belongsTo(SubModule::class);
    }

    // Relasi: Slot ini memiliki banyak file unggahan
    public function submissions()
    {
        return $this->hasMany(PracticumSubmission::class);
    }
}
