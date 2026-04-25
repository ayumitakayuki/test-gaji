<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GenerateKaryawanAccountsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (\App\Models\Karyawan::all() as $karyawan) {
            // Kalau karyawan sudah terhubung ke user, lewati
            if ($karyawan->user_id) {
                continue;
            }

            // Email dari nama karyawan
            $localPart = preg_replace('/[^a-z0-9.]/', '', strtolower(str_replace(' ', '.', $karyawan->nama)));
            $email = $localPart . '@rku.absensi';

            // Kalau email sudah ada, pakai user itu. Kalau belum ada, buat baru.
            $user = \App\Models\User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $karyawan->nama,
                    'password' => Hash::make('123456'),
                ]
            );

            // Hubungkan user dengan karyawan
            $user->karyawan_id = $karyawan->id;
            $user->save();

            // Kasih role karyawan
            if (!$user->hasRole('karyawan')) {
                $user->assignRole('karyawan');
            }

            // Set user_id di tabel karyawan
            $karyawan->user_id = $user->id;
            $karyawan->save();
        }
    }
}