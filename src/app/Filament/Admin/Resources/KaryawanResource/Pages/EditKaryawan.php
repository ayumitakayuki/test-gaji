<?php

namespace App\Filament\Admin\Resources\KaryawanResource\Pages;

use App\Filament\Admin\Resources\KaryawanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKaryawan extends EditRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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