<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        try {
            $this->record->syncRoles($this->data['roles'] ?? []);
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal menyimpan roles')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }
    }

}
