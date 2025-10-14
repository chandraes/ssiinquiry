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

    public function hasRole(string $roleSlug): bool
    {
        return $this->roles->contains('slug', $roleSlug);
    }

    public function assignRole(string $roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->firstOrFail();
        $this->roles()->syncWithoutDetaching($role);
    }
}
