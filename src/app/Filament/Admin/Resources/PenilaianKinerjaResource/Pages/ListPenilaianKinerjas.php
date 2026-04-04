<?php

namespace App\Filament\Admin\Resources\PenilaianKinerjaResource\Pages;

use App\Filament\Admin\Resources\PenilaianKinerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenilaianKinerjas extends ListRecords
{
    protected static string $resource = PenilaianKinerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}