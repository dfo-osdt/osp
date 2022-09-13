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
        \App\Models\User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@test.com',
        ]);

        $this->call([
            RegionSeeder::class,
            OrganizationSeeder::class,
        ]);
    }
}
