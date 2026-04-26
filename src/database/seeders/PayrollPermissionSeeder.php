<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class PayrollPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // penting: reset cache sebelum create/sync permission
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::transaction(function () {

            $permissions = [
                // Karyawan
                'absensi.create',
                'kasbon.request',
                'penggajian.view_own',

                // Staff Admin
                'penggajian.process',
                'penggajian.report',
                'absensi.validate',
                'karyawan.manage',

                // Staff Kasbon
                'kasbon.process',
                'kasbon.view_all',

                // Direktur OP
                'dashboard.view',
                'activity.view',
                'user.manage',
                'role.manage',
                'kinerja.manage',
                'kasbon.approve',
                'penggajian.approve',
            ];

            foreach ($permissions as $perm) {
                Permission::firstOrCreate([
                    'name' => $perm,
                    'guard_name' => 'web',
                ]);
            }

            // reset cache lagi setelah permission dibuat
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            $karyawan = Role::firstOrCreate(['name' => 'karyawan', 'guard_name' => 'web']);
            $staffKasbon = Role::firstOrCreate(['name' => 'staff_kasbon', 'guard_name' => 'web']);
            $staffAdmin = Role::firstOrCreate(['name' => 'staff_administrasi', 'guard_name' => 'web']);
            $direktur = Role::firstOrCreate(['name' => 'direktur_operasional', 'guard_name' => 'web']);

            $karyawan->syncPermissions([
                'absensi.create',
                'kasbon.request',
                'penggajian.view_own',
            ]);

            $staffKasbon->syncPermissions([
                'kasbon.process',
                'kasbon.view_all',
            ]);

            $staffAdmin->syncPermissions([
                'penggajian.process',
                'penggajian.report',
                'absensi.validate',
                'karyawan.manage',
            ]);

            $direktur->syncPermissions([
                'dashboard.view',
                'activity.view',
                'user.manage',
                'role.manage',
                'kasbon.approve',
                'penggajian.approve',
                'kinerja.manage',
            ]);

            // Jalankan ini hanya kalau method syncWithParentPermissions()
            // memang ada di model Role kamu dan parent-role logic masih dipakai.
            // if (method_exists($staffKasbon, 'syncWithParentPermissions')) {
            //     $staffKasbon->syncWithParentPermissions();
            // }

            // if (method_exists($staffAdmin, 'syncWithParentPermissions')) {
            //     $staffAdmin->syncWithParentPermissions();
            // }
        });

        // final reset cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}