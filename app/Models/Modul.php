<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = 'moduls';

    protected $fillable = [
        'judul_id',
        'judul_en',
        'deskripsi',
        'owner',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner');
    }
    
}
