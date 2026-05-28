<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected array $selectedRoles = [];

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate(): void
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->selectedRoles = $data['roles'] ?? [];

        unset($data['roles']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->syncRoles($this->selectedRoles);
    }
}