<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Enums\RoleName;
use App\Filament\Admin\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $roleName = $data['role_name'] ?? RoleName::User->value;

        unset($data['role_name']);

        /** @var \App\Models\User $record */
        $record = parent::handleRecordCreation($data);
        $record->syncRoles([$roleName]);

        return $record;
    }
}