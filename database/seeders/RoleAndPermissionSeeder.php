<?php

namespace Database\Seeders;

use App\Enums\Permissions\UserPermission;
use App\Enums\Permissions\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        collect(UserPermission::cases())
            ->each(fn ($permission) => Permission::findOrCreate($permission->value));

        collect(UserRole::cases())->each(function ($userRole) {
            $role = Role::findOrCreate($userRole->value);
            $role->syncPermissions($userRole->permissionValues());
        });
    }
}
