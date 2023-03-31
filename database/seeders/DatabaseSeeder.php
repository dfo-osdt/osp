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
    }
}
