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
                'email' => 'author@test.com',
            ]);

            // create 3 manuscript records for the test user
            \App\Models\ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(5))->count(3)->create([
                'user_id' => $user->id,
            ]);

            // create 1 filled out manuscript record for the test user
            \App\Models\ManuscriptRecord::factory()->filled()->create([
                'user_id' => $user->id,
            ]);

            // create a division manager user
            $dmUser = \App\Models\User::factory()->create([
                'first_name' => 'Division',
                'last_name' => 'Manager',
                'email' => 'dm@test.com',
            ]);

            // create a rds user
            $rdsUser = \App\Models\User::factory()->create([
                'first_name' => 'RDS',
                'last_name' => 'User',
                'email' => 'rds@test.com',
            ]);
        }
    }
}
