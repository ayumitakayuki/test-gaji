<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class PayrollPermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // ---------------------------
            // 1) Permissions
            // ---------------------------
            $permissions = [
                // Karyawan
                'absensi.create',
                'kasbon.request',
                'penggajian.view_own',

                // Staff Kasbon
                'kasbon.process',
                'kasbon.view_all',

                // Staff Admin
                'dashboard.view',
                'activity.view',
                'user.manage',
                'role.manage',
                'kasbon.approve',
                'penggajian.approve',
            ];

            foreach ($permissions as $perm) {
                Permission::firstOrCreate([
                    'name' => $perm,
                    'guard_name' => 'web',
                ]);
            }

            // ---------------------------
            // 2) Roles
            // ---------------------------
            $karyawan = Role::where('name', 'karyawan')->firstOrFail();
            $staffKasbon = Role::where('name', 'staff_kasbon')->firstOrFail();
            $staffAdmin = Role::where('name', 'staff_administrasi')->firstOrFail();
            $direktur = Role::where('name', 'direktur_operasional')->firstOrFail();

            // ---------------------------
            // 3) Assign permission to roles
            // ---------------------------

            // Karyawan
            $karyawan->syncPermissions([
                'absensi.create',
                'kasbon.request',
                'penggajian.view_own',
            ]);

            // Staff kasbon (inherit karyawan permissions + kasbon management)
            $staffKasbon->syncPermissions([
                // allow staff kasbon to request
                'kasbon.process',
                'kasbon.view_all',
            ]);

            // Staff admin (inherit karyawan permissions + admin duties)
            $staffAdmin->syncPermissions([
                'penggajian.process',
                'penggajian.report',
                'absensi.validate',
                'karyawan.manage',
            ]);
            $staffKasbon->syncWithParentPermissions();
            $staffAdmin->syncWithParentPermissions();

            // Direktur Operasional (can request and approve kasbon)
            $direktur->syncPermissions([
                'dashboard.view',
                'activity.view',
                'user.manage',
                'role.manage',
                'kasbon.approve',
                'penggajian.approve',
                'kinerja.manage',
            ]);
        });
    }
}