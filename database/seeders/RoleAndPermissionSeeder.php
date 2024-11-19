<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Enums\Permissions\UserRole;

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

        // create author permissions
        $authorPermissions = collect(UserRole::AUTHOR->permissions())
            ->map(fn($permission) => Permission::findOrCreate($permission));

        $authorRole = Role::findOrCreate(UserRole::AUTHOR->value);
        $authorRole->syncPermissions($authorPermissions);

        // create director permissions
        $directorPermissions = collect(UserRole::DIRECTOR->permissions())
            ->map(fn($permission) => Permission::findOrCreate($permission));

        $directorRole = Role::findOrCreate(UserRole::DIRECTOR->value);
        $directorRole->syncPermissions($directorPermissions);

        // create admin permissions
        $adminPermissions = collect(UserRole::ADMIN->permissions())
            ->map(fn($permission) => Permission::findOrCreate($permission));

        $adminRole = Role::findOrCreate(UserRole::ADMIN->value);
        $adminRole->syncPermissions($adminPermissions);
    }
}
