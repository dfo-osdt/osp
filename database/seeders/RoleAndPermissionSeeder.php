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
            Permission::create(['name' => 'create_manuscript_records']),
            Permission::create(['name' => 'create_publications']),
            Permission::create(['name' => 'create_authors']),
            Permission::create(['name' => 'update_authors']),
            Permission::create(['name' => 'create_organizations']),
        ]);

        $authorRole = Role::create(['name' => 'author']);
        $authorRole->syncPermissions($authorPermissions);

        // what permission does have RDS or DG have, add them here
        // We also merge all author permissions so do not duplicate.
        $directorPermissions = collect([
            Permission::create(['name' => 'withhold_and_complete_management_review']),
        ])->merge($authorPermissions);

        $directorRole = Role::create(['name' => 'director']);
        $directorRole->syncPermissions($directorPermissions);
    }
}
