<?php

namespace App\Filament\Admin\Resources\PerizinanResource\Pages;

use App\Filament\Admin\Resources\PerizinanResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePerizinan extends CreateRecord
{
    protected static string $resource = PerizinanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Auth::user()->role == 'karyawan' && empty($data['karyawan_id'])) {
            $data['karyawan_id'] = Auth::user()->karyawan_id;
        }

        return $data;
    }
}