<?php

namespace App\Filament\Admin\Resources\PerizinanResource\Pages;

use App\Filament\Admin\Resources\PerizinanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerizinans extends ListRecords
{
    protected static string $resource = PerizinanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
