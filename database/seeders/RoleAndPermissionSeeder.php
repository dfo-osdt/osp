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
            Permission::findOrCreate('create_manuscript_records'),
            Permission::findOrCreate('create_publications'),
            Permission::findOrCreate('create_authors'),
            Permission::findOrCreate('update_authors'),
            Permission::findOrCreate('create_organizations'),
            Permission::findOrCreate('create_author_employments'),
        ]);

        $authorRole = Role::findOrCreate('author');
        $authorRole->syncPermissions($authorPermissions);

        // what permission does have RDS or DG have, add them here
        // We also merge all author permissions so do not duplicate.
        $directorPermissions = collect([
            Permission::findOrCreate('withhold_and_complete_management_review'),
        ])->merge($authorPermissions);

        $directorRole = Role::findOrCreate('director');
        $directorRole->syncPermissions($directorPermissions);

        // create initial admin user
        $adminPermissions = collect([
            Permission::findOrCreate('view_admin_dashboard'),
            Permission::findOrCreate('view_telescope'),
            Permission::findOrCreate('view_horizon'),
            Permission::findOrCreate('view_pulse'),
        ]);

        $adminRole = Role::findOrCreate('admin');
        $adminRole->syncPermissions($adminPermissions);
    }
}
