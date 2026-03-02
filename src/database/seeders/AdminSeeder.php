<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // ✅ 1) SUPER ADMIN (wajib)
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Admin',
                'password' => $defaultPassword,
            ]
        );

        // Pastikan super admin selalu super_admin walaupun sebelumnya pernah diganti
        $admin->syncRoles(['super_admin']);

        // ✅ 2) STAFF ADMINISTRASI
        $staffAdmin = User::firstOrCreate(
            ['email' => 'staffadmin@demo.com'],
            [
                'name'     => 'Staff Administrasi',
                'password' => $defaultPassword,
            ]
        );
        $staffAdmin->syncRoles(['staff_administrasi']);

        // ✅ 3) STAFF KASBON
        $staffKasbon = User::firstOrCreate(
            ['email' => 'kasbon@demo.com'],
            [
                'name'     => 'Staff Kasbon',
                'password' => $defaultPassword,
            ]
        );
        $staffKasbon->syncRoles(['staff_kasbon']);

        // ✅ 4) DIREKTUR OPERASIONAL
        $direktur = User::firstOrCreate(
            ['email' => 'direktur@demo.com'],
            [
                'name'     => 'Direktur Operasional',
                'password' => $defaultPassword,
            ]
        );
        $direktur->syncRoles(['direktur_operasional']);

        // ✅ 5) KARYAWAN
        $karyawan = User::firstOrCreate(
            ['email' => 'karyawan@demo.com'],
            [
                'name'     => 'Karyawan',
                'password' => $defaultPassword,
            ]
        );
        $karyawan->syncRoles(['karyawan']);

        // ✅ Optional: log hasil seed
        Log::info('AdminSeeder berhasil membuat user demo payroll', [
            'admin' => $admin->email,
            'staff_admin' => $staffAdmin->email,
            'staff_kasbon' => $staffKasbon->email,
            'direktur' => $direktur->email,
            'karyawan' => $karyawan->email,
        ]);
    }
}
