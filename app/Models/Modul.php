<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Modul extends Model
{
    use HasTranslations;
    protected $table = 'moduls';

    protected $translatable = ['judul', 'deskripsi'];
    protected $guarded = [];

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

    public function subModules()
    {
        return $this->hasMany(SubModule::class, 'modul_id');
    }
    
    public function getRelatedPhyphoxAttribute()
    {
        // Pastikan kolom phyphox_id memiliki nilai dan berbentuk array
        $phyphoxIds = $this->phyphox_id ?? [];

        if (empty($phyphoxIds)) {
            return collect(); // Mengembalikan koleksi kosong jika tidak ada ID
        }

        // Menggunakan whereIn untuk mencari semua model Phyphox berdasarkan ID
        return Phyphox::whereIn('id', $phyphoxIds)->get();
    }
}
