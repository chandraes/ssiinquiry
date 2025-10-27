<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

    public function profile()
    {
        return $this->hasOne(ProfileUser::class);
    }

    public function moduls()
    {
        return $this->belongsToMany(Modul::class, 'modul_user', 'user_id', 'modul_id');
    }

    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'kelas_users', 'user_id', 'kelas_id');
    }

    public function subModuleProgress(): HasMany
    {
        return $this->hasMany(SubModuleProgress::class, 'user_id');
    }

    public function forumTeams()
    {
        return $this->hasMany(ForumTeam::class, 'user_id');
    }

    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class, 'user_id');
    }


}
