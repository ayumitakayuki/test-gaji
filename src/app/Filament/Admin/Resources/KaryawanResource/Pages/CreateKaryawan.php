<?php

namespace App\Filament\Admin\Resources\KaryawanResource\Pages;

use App\Filament\Admin\Resources\KaryawanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKaryawan extends CreateRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['status'] ?? null) === 'harian tetap') {
            $data['gaji_lembur'] = round((($data['gaji_setengah_bulan'] ?? 0) * 2) / 174);
        }

        if (($data['status'] ?? null) === 'harian lepas') {
            $data['gaji_lembur'] = round((($data['gaji_harian'] ?? 0) * 21) / 174);
        }

        return $data;
    }
}