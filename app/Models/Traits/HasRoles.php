<?php

namespace App\Models\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Cek apakah user memiliki role tertentu.
     *
     * @param string $roleSlug
     * @return bool
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles->contains('slug', $roleSlug);
    }

    /**
     * ✅ Tambahkan method baru ini
     * Cek apakah user memiliki salah satu dari role yang diberikan dalam array.
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            // Kita gunakan lagi method hasRole() yang sudah ada
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Berikan role kepada user.
     *
     * @param string $roleSlug
     * @return void
     */
    public function assignRole(string $roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->firstOrFail();
        $this->roles()->syncWithoutDetaching($role);
    }
}
