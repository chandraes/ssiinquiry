<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = 'moduls';

    protected $fillable = [
        'judul_id',
        'judul_en',
        'deskripsi_id',
        'deskripsi_en',
        'phyphox_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'phyphox_id' => 'array', // <-- TAMBAHKAN INI
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'modul_user', 'modul_id', 'user_id');
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'modul_user', 'modul_id', 'user_id');
    }

    public function kelas()
{
    return $this->hasMany(Kelas::class, 'modul_id');
}


    
}
