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
                'kasbon.validate',
                'kasbon.process',
                'kasbon.view_all',
                'kasbon.approve',

                // Staff Admin
                'karyawan.manage',
                'absensi.validate',
                'penggajian.process',
                'penggajian.report_generate',
                'penggajian.view_all',
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

            // Staff kasbon (tanpa inherit dulu, nanti kita buat inheritance)
            $staffKasbon->syncPermissions([
                'kasbon.validate',
                'kasbon.process',
                'kasbon.view_all',
            ]);

            // Staff admin
            $staffAdmin->syncPermissions([
                'karyawan.manage',
                'absensi.validate',
                'penggajian.process',
                'penggajian.report_generate',
                'penggajian.view_all',
            ]);
            $staffKasbon->syncWithParentPermissions();
            $staffAdmin->syncWithParentPermissions();

            // Direktur Operasional
            $direktur->syncPermissions([
                'kasbon.approve',
                'penggajian.approve',
                'penggajian.view_all',
            ]);
        });
    }
}
