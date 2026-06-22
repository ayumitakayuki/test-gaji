<?php

namespace App\Services;

use App\Models\Gaji;
use App\Models\Karyawan;
use App\Filament\Admin\Pages\SlipGajiHitung;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class SlipGajiBatchService
{
    public function karyawanRekapQuery(string $startDate, string $endDate): Builder
    {
        return Karyawan::query()
            ->whereHas('rekaps', function ($query) use ($startDate, $endDate) {
                $query
                    ->whereDate('periode_awal', $startDate)
                    ->whereDate('periode_akhir', $endDate);
            })
            ->orderBy('id', 'asc');
    }

    public function generateManyByPeriod(
        string $startDate,
        string $endDate,
        string $tipePembayaran = 'payroll'
    ): array {
        $karyawans = $this->karyawanRekapQuery($startDate, $endDate)->get();

        $result = [
            'total' => $karyawans->count(),
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($karyawans as $karyawan) {
            try {
                $this->generateOne($karyawan, $startDate, $endDate, $tipePembayaran);
                $result['success']++;
            } catch (Throwable $e) {
                $result['failed']++;
                $result['errors'][] = $karyawan->nama . ': ' . $e->getMessage();
            }
        }

        return $result;
    }

    public function generateOne(
        Karyawan $karyawan,
        string $startDate,
        string $endDate,
        string $tipePembayaran = 'payroll'
    ): void {
        $existingGaji = Gaji::query()
            ->where('id_karyawan', $karyawan->id_karyawan)
            ->whereDate('periode_awal', $startDate)
            ->whereDate('periode_akhir', $endDate)
            ->where('tipe_pembayaran', $tipePembayaran)
            ->first();

        /** @var SlipGajiHitung $page */
        $page = app(SlipGajiHitung::class);

        $page->editingGajiId = $existingGaji?->id;
        $page->karyawan_id = $karyawan->id_karyawan;
        $page->selected_id = $karyawan->id_karyawan;
        $page->start_date = $startDate;
        $page->end_date = $endDate;
        $page->tipe_pembayaran = $tipePembayaran;
        $page->additional_items = [];

        $page->hitungGaji();
        $page->simpanSlipGaji();
    }
}