<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ReflectionQuestion extends Model
{
    use HasTranslations;
    public $translatable = ['question_text'];
    protected $fillable = ['sub_module_id', 'question_text', 'order'];

    public function subModule()
    {
        return $this->belongsTo(SubModule::class);
    }

    public function answers()
    {
        return $this->hasMany(ReflectionAnswer::class);
    }
}
