<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait EnforceSoD
{
    public function assignRole(...$roles)
    {
        $this->checkSoD($roles);

        // Panggil method asli Spatie HasRoles (via alias di User.php)
        return $this->_spatieAssignRole(...$roles);
    }

    public function syncRoles(...$roles)
    {
        $this->checkSoD($roles);

        // Panggil method asli Spatie HasRoles (via alias di User.php)
        return $this->_spatieSyncRoles(...$roles);
    }

    private function checkSoD(array $roles)
    {
        $roleNames = collect($roles)->flatten()->toArray();

        $newRoleIds = \App\Models\Role::whereIn('name', $roleNames)->pluck('id')->toArray();
        $existingRoleIds = $this->roles()->pluck('id')->toArray();

        foreach ($existingRoleIds as $existingId) {
            foreach ($newRoleIds as $newId) {
                $conflict = DB::table('role_conflicts')
                    ->where(function($q) use ($existingId, $newId) {
                        $q->where('role_id', $existingId)->where('conflict_role_id', $newId);
                    })
                    ->orWhere(function($q) use ($existingId, $newId) {
                        $q->where('role_id', $newId)->where('conflict_role_id', $existingId);
                    })
                    ->exists();

                if ($conflict) {
                    $existingRole = \App\Models\Role::find($existingId)->name;
                    $newRole = \App\Models\Role::find($newId)->name;

                    throw new \Exception("SoD Violation: {$newRole} konflik dengan {$existingRole}");
                }
            }
        }
    }
}
