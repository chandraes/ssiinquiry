<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class SubModule extends Model
{
    use HasTranslations; // <--- 2. Gunakan Trait

    // 3. Tentukan kolom mana yang bisa diterjemahkan
    public $translatable = ['title', 'description', 'debate_topic'];

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        'modul_id',
        'type',
        'title',
        'description',
        'order',
        'debate_topic',
        'debate_rules',
        'debate_start_time',
        'debate_end_time',
        'phase1_end_time',
        'phase2_end_time',
    ];

    protected $casts = [
        'debate_start_time' => 'datetime',
        'debate_end_time' => 'datetime',
        'phase1_end_time' => 'datetime',
        'phase2_end_time' => 'datetime',
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
        return $this->hasMany(PracticumUploadSlot::class, 'sub_module_id')->orderBy('order');
    }

    public function forumTeams()
    {
        return $this->hasMany(ForumTeam::class);
    }

    public function forumPosts()
    {
        // Ambil hanya postingan utama (bukan balasan) dan urutkan
        return $this->hasMany(ForumPost::class)
                    ->whereNull('parent_post_id')
                    ->latest();
    }

    public function studentProgress(): HasMany
    {
        return $this->hasMany(SubModuleProgress::class, 'sub_module_id');
    }
}
