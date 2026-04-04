<?php

namespace App\Filament\Admin\Resources\PenilaianKinerjaResource\Pages;

use App\Filament\Admin\Resources\PenilaianKinerjaResource;
use App\Services\PenilaianKinerjaService;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPenilaianKinerja extends EditRecord
{
    protected static string $resource = PenilaianKinerjaResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $hasil = app(PenilaianKinerjaService::class)->hitung($data);

        $data['nilai_akhir'] = $hasil['nilai_akhir'];
        $data['predikat'] = $hasil['predikat'];
        $data['penilai_user_id'] = Auth::id();

        if (empty($data['nominal_kenaikan_gaji']) || (int) $data['nominal_kenaikan_gaji'] === 0) {
            $data['nominal_kenaikan_gaji'] = $hasil['nominal_kenaikan_gaji'];
        }

        return $data;
    }
}