<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileUser extends Model
{
    protected $table = 'profile_users';

    protected $fillable=[
        'user_id',
        'foto',
        'nomer_hp',
        'asal_sekolah'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
