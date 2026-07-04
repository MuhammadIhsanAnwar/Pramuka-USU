<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Enums\RoleName;
use App\Filament\Admin\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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
}