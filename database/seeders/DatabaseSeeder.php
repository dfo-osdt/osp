<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RegionSeeder::class,
            OrganizationSeeder::class,
            FunderSeeder::class,
            RoleAndPermissionSeeder::class,
            FunctionalAreaSeeder::class,
        ]);

        if (config('app.env') === 'local') {
            Log::debug('Seeding env: '.config('app.env').'..');
            // ask user if they want to seed the database with test data
            if ($this->command->confirm('Do you want to seed the database with test data?')) {
                $this->command->info('Seeding the database with test data...');
                $this->call([
                    LocalTestDataSeeder::class,
                ]);
                $this->call(JournalsTableSeeder::class);
    }
        }
    }
}
