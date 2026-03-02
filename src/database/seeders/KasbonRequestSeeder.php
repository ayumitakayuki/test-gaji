<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KasbonRequest;
use App\Models\Karyawan;

class KasbonRequestSeeder extends Seeder
{
    public function run(): void
    {
        $karyawan = Karyawan::first();

        if (!$karyawan) return;

        KasbonRequest::create([
            'karyawan_id' => $karyawan->id,
            'tanggal_pengajuan' => now(),
            'nominal' => 500000,
            'tenor' => 1,
            'cicilan' => 500000,
            'alasan_pengajuan' => 'Pembayaran sewa rumah',
            'status_awal' => 'waiting_do_awal',
            'status_akhir' => 'draft',
        ]);

        KasbonRequest::create([
            'karyawan_id' => $karyawan->id,
            'tanggal_pengajuan' => now(),
            'nominal' => 1000000,
            'tenor' => 2,
            'cicilan' => 500000,
            'alasan_pengajuan' => 'Biaya mendadak keluarga',
            'status_awal' => 'approved_do_awal',
            'status_akhir' => 'waiting_staff_akhir',
        ]);
    }
}
