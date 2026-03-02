<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KasbonLoan;

class KasbonApprovalQueueSeeder extends Seeder
{
    public function run(): void
    {
        // ambil beberapa loan yang sudah ada
        $loans = KasbonLoan::take(5)->get();

        foreach ($loans as $i => $loan) {
            if ($i % 2 == 0) {
                // masuk queue approval awal DO
                $loan->update([
                    'status_awal' => 'waiting_do_awal',
                    'status_akhir' => 'draft',
                ]);
            } else {
                // masuk queue approval akhir DO
                $loan->update([
                    'status_awal' => 'approved_do_awal',
                    'status_akhir' => 'waiting_do_akhir',
                    'prioritas' => 'tinggi',
                ]);
            }
        }
    }
}
