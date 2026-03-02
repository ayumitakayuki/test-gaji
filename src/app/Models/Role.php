<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends SpatieRole
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Role::class, 'parent_id');
    }

    public function inheritedPermissions()
    {
        $permissions = $this->permissions;
        $parent = $this->parent;

        while ($parent) {
            $permissions = $permissions->merge($parent->permissions);
            $parent = $parent->parent;
        }

        return $permissions->unique('id');
    }
    public function syncWithParentPermissions(): void
    {
        $parentPerms = $this->parent ? $this->parent->permissions : collect();
        $allPerms = $this->permissions->merge($parentPerms)->unique('id');

        $this->syncPermissions($allPerms);
    }

}
