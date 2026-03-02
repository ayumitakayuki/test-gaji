<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleAssignmentService
{
    public function assignRole(User $user, string $roleName): void
    {
        $role = Role::where('name', $roleName)->firstOrFail();

        foreach ($user->roles as $existingRole) {
            if ($this->isConflict($existingRole->id, $role->id)) {
                throw new \Exception("SoD Violation: {$role->name} konflik dengan {$existingRole->name}");
            }
        }

        $user->assignRole($role);
    }

    private function isConflict(int $roleA, int $roleB): bool
    {
        return DB::table('role_conflicts')
            ->where(function ($q) use ($roleA, $roleB) {
                $q->where('role_id', $roleA)->where('conflict_role_id', $roleB);
            })
            ->orWhere(function ($q) use ($roleA, $roleB) {
                $q->where('role_id', $roleB)->where('conflict_role_id', $roleA);
            })
            ->exists();
    }
}
