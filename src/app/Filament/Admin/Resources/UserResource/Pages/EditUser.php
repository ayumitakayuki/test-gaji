<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected array $selectedRoles = [];

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

    protected function beforeSave(): void
    {
        $roles = $this->data['roles'] ?? [];

        $message = User::getSoDConflictMessage($roles);

        if ($message) {
            Notification::make()
                ->title('Gagal menyimpan role')
                ->body($message)
                ->danger()
                ->send();

            $this->addError('data.roles', $message);

            $this->halt();
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->selectedRoles = $data['roles'] ?? [];

        unset($data['roles']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->syncRoles($this->selectedRoles);
    }
}