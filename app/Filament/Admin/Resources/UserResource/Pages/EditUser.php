<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Enums\RoleName;
use App\Filament\Admin\Resources\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['role_name'] = $this->record->getRoleNames()->first() ?? RoleName::User->value;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $roleName = $data['role_name'] ?? RoleName::User->value;

        unset($data['role_name']);

        $record = parent::handleRecordUpdate($record, $data);
        $record->syncRoles([$roleName]);

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => Filament::auth()->id() !== $this->record->id),
        ];
    }

    protected function canDelete(Model $record): bool
    {
        return Filament::auth()->id() !== $record->id && parent::canDelete($record);
    }
}