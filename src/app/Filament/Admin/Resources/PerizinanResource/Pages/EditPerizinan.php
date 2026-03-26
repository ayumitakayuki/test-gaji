<?php

namespace App\Filament\Admin\Resources\PerizinanResource\Pages;

use App\Filament\Admin\Resources\PerizinanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerizinan extends EditRecord
{
    protected static string $resource = PerizinanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
