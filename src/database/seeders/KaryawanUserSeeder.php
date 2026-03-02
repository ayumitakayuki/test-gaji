<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;

class KaryawanUserSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ buat data karyawan
        $karyawan = Karyawan::firstOrCreate([
            'nama' => 'Karyawan Demo',
        ], [
            'status' => 'staff',
            'lokasi' => 'workshop',
            'bagian' => 'Produksi',
        ]);

        // ✅ buat user login karyawan & hubungkan karyawan_id
        $user = User::updateOrCreate([
            'email' => 'karyawan@test.com',
        ], [
            'name' => 'Karyawan Demo',
            'password' => Hash::make('password'),
            'karyawan_id' => $karyawan->id,
        ]);

        // ✅ assign role karyawan
        $user->syncRoles(['karyawan']);
    }
}
