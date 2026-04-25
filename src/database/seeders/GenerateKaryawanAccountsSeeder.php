<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GenerateKaryawanAccountsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (\App\Models\Karyawan::all() as $karyawan) {
            if ($karyawan->user_id) {
                continue;
            }

            // normalisasi nama -> nama@rku.absensi
            $localPart = preg_replace('/[^a-z0-9.]/', '', strtolower(str_replace(' ', '.', $karyawan->nama)));
            $email = $localPart . '@rku.absensi';

            $user = \App\Models\User::create([
                'name'     => $karyawan->nama,
                'email'    => $email,
                'password' => Hash::make('123456'),
            ]);
            $user->karyawan_id = $karyawan->id;
            $user->save();

            $user->assignRole('karyawan');
            $karyawan->user_id = $user->id;
            $karyawan->save();
        }
    }
}