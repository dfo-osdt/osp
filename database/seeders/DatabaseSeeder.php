<?php

namespace Database\Seeders;

use App\Models\ManuscriptAuthor;
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
        ]);

        // here to test the system / demo - remove later
        // do not use in testing

        if (config('app.env') != 'testing') {
            $user = \App\Models\User::factory()->create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'test@test.com',
            ]);

            // create 3 manuscript records for the test user
            \App\Models\ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(5))->count(3)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
