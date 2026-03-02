<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class PayrollRoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Base role
            $karyawan = Role::firstOrCreate([
                'name' => 'karyawan',
                'guard_name' => 'web',
            ], [
                'level' => 1,
                'parent_id' => null,
            ]);

            // Staff roles inherit from karyawan
            $staffAdmin = Role::firstOrCreate([
                'name' => 'staff_administrasi',
                'guard_name' => 'web',
            ], [
                'level' => 2,
                'parent_id' => $karyawan->id,
            ]);

            $staffKasbon = Role::firstOrCreate([
                'name' => 'staff_kasbon',
                'guard_name' => 'web',
            ], [
                'level' => 2,
                'parent_id' => $karyawan->id,
            ]);

            // Direktur berdiri sendiri (no parent)
            $direktur = Role::firstOrCreate([
                'name' => 'direktur_operasional',
                'guard_name' => 'web',
            ], [
                'level' => 3,
                'parent_id' => null,
            ]);

            // SoD Conflict rules (staff tidak boleh merangkap direktur)
            DB::table('role_conflicts')->insertOrIgnore([
                [
                    'role_id' => $staffAdmin->id,
                    'conflict_role_id' => $direktur->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'role_id' => $staffKasbon->id,
                    'conflict_role_id' => $direktur->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        });
    }
}
