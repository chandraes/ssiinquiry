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

    /**
     * Membuat accessor untuk mengambil data Phyphox yang terkait
     * berdasarkan ID yang tersimpan di kolom phyphox_id (JSON).
     * * @return \Illuminate\Database\Eloquent\Collection
     */
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
