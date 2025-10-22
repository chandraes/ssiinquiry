<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phyphox extends Model
{
    use HasFactory;

    protected $table = 'phyphox';

    protected $fillable = [
        'kategori',
        'nama',
        'is_active',
        'icon',
    ];
}
