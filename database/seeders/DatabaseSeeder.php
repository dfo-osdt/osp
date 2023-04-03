<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
        ]);

        if (config('app.env') !== 'testing') {
            $this->call([
                DfoSeriesJournalSeeder::class, // enable this seeder when we go live
                JournalsTableSeeder::class,
            ]);
        }

        if (config('app.env') === 'local') {
            // ask user if they want to seed the database with test data
            if ($this->command->confirm('Do you want to seed the database with test data?')) {
                $this->command->info('Seeding the database with test data...');
                $this->call([
                    LocalTestDataSeeder::class,
                ]);
            }
        }
    }
}
