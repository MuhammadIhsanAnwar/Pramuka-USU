<?php

namespace Database\Seeders;

use App\Enums\PermissionName;
use App\Enums\RoleName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(PermissionName::cases())
            ->map(static fn (PermissionName $permission): array => [
                'name' => $permission->value,
                'guard_name' => 'web',
            ]);

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate($permission);
        }

        $adminRole = Role::query()->firstOrCreate([
            'name' => RoleName::Admin->value,
            'guard_name' => 'web',
        ]);

        $userRole = Role::query()->firstOrCreate([
            'name' => RoleName::User->value,
            'guard_name' => 'web',
        ]);

        $adminRole->syncPermissions(Permission::query()->pluck('name')->all());

        $userRole->syncPermissions([
            PermissionName::NewsView->value,
            PermissionName::NewsCreate->value,
            PermissionName::NewsUpdate->value,
            PermissionName::NewsDelete->value,
            PermissionName::NewsOwnManage->value,
            PermissionName::AgendaView->value,
            PermissionName::GalleryView->value,
            PermissionName::AttendanceScan->value,
            PermissionName::AttendanceHistoryView->value,
            PermissionName::ProfileUpdate->value,
        ]);
    }
}