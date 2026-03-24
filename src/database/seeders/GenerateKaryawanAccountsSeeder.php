<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GenerateKaryawanAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        foreach (\App\Models\Karyawan::all() as $karyawan) {
            // periksa apakah karyawan sudah punya user yang terhubung
            if ($karyawan->user_id) {
                continue;
            }

            $email = $karyawan->id_karyawan . '@absensi.test';
            // buat user tanpa karyawan_id, lalu setel manual agar lolos mass assignment
            $user = \App\Models\User::create([
                'name'     => $karyawan->nama,
                'email'    => $email,
                'password' => Hash::make('password'),
            ]);
            $user->karyawan_id = $karyawan->id;
            $user->save();

            $user->assignRole('karyawan');
            // setel user_id di karyawan untuk relasi belongsTo
            $karyawan->user_id = $user->id;
            $karyawan->save();
        }
    }
}
