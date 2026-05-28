<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait EnforceSoD
{
    public function assignRole(...$roles)
    {
        $incomingRoles = $this->normalizeRoleNames($roles);

        $finalRoles = $this->roles()
            ->pluck('name')
            ->merge($incomingRoles)
            ->unique()
            ->values()
            ->toArray();

        static::assertNoSoDConflict($finalRoles);

        return $this->_spatieAssignRole(...$roles);
    }

    public function syncRoles(...$roles)
    {
        $incomingRoles = $this->normalizeRoleNames($roles);

        static::assertNoSoDConflict($incomingRoles);

        return $this->_spatieSyncRoles(...$roles);
    }

    public static function assertNoSoDConflict(array $roleNames): void
    {
        $message = static::getSoDConflictMessage($roleNames);

        if ($message) {
            throw ValidationException::withMessages([
                'roles' => $message,
            ]);
        }
    }

    public static function getSoDConflictMessage(array $roleNames): ?string
    {
        $roleNames = collect($roleNames)
            ->flatten()
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (count($roleNames) < 2) {
            return null;
        }

        $roles = Role::query()
            ->whereIn('name', $roleNames)
            ->get(['id', 'name'])
            ->values();

        for ($i = 0; $i < $roles->count(); $i++) {
            for ($j = $i + 1; $j < $roles->count(); $j++) {
                $roleA = $roles[$i];
                $roleB = $roles[$j];

                $isConflict = DB::table('role_conflicts')
                    ->where(function ($query) use ($roleA, $roleB) {
                        $query->where('role_id', $roleA->id)
                            ->where('conflict_role_id', $roleB->id);
                    })
                    ->orWhere(function ($query) use ($roleA, $roleB) {
                        $query->where('role_id', $roleB->id)
                            ->where('conflict_role_id', $roleA->id);
                    })
                    ->exists();

                if ($isConflict) {
                    return "Konflik SoD: role {$roleA->name} tidak boleh digabung dengan role {$roleB->name}.";
                }
            }
        }

        return null;
    }

    private function normalizeRoleNames(array $roles): array
    {
        return collect($roles)
            ->flatten()
            ->map(function ($role) {
                if ($role instanceof Role) {
                    return $role->name;
                }

                if (is_string($role)) {
                    return $role;
                }

                if (is_int($role)) {
                    return Role::find($role)?->name;
                }

                return null;
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }
}