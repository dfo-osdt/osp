<?php

namespace Database\Seeders;

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

        // create author permissions
        $authorPermissions = collect([
            Permission::create(['name' => 'create_manuscripts_records']),
            Permission::create(['name' => 'create_publications']),
            Permission::create(['name' => 'create_authors']),
            Permission::create(['name' => 'update_authors']),
            Permission::create(['name' => 'create_organizations']),
        ]);

        $authorRole = Role::create(['name' => 'author']);
        $authorRole->syncPermissions($authorPermissions);
    }
}
