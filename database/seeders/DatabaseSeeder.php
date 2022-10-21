<?php

namespace Database\Seeders;

use App\Models\ManagementReviewStep;
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
            RoleAndPermissionSeeder::class,
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

            // create 1 manuscript record for the test user with a review step
            $toReview = \App\Models\ManuscriptRecord::factory()->in_review()->create([
                'user_id' => $user->id,
            ]);

            // create a division manager user
            $dmUser = \App\Models\User::factory()->create([
                'first_name' => 'Division',
                'last_name' => 'Manager',
                'email' => 'dm@test.com',
            ]);

            // give the division manager a management review step
            ManagementReviewStep::factory()->create([
                'user_id' => $dmUser->id,
                'manuscript_record_id' => $toReview->id,
            ]);

            // create a rds user
            $rdsUser = \App\Models\User::factory()->create([
                'first_name' => 'RDS',
                'last_name' => 'User',
                'email' => 'rds@test.com',
            ]);

            // Make an author for Mark
            $markAuthor = \App\Models\Author::factory()->create([
                'first_name' => 'Mark',
                'last_name' => 'LaFlamme',
                'email' => 'mark.laflamme@dfo-mpo.gc.ca',
                'organization_id' => 1,
                'orcid' => '0000-0001-5955-7098',
            ]);

            $markUser = \App\Models\User::factory()->create([
                'first_name' => 'Mark',
                'last_name' => 'LaFlamme',
                'email' => 'mark.laflamme@dfo-mpo.gc.ca',
            ]);

            // create a manuscript record for Mark
            $marksManuscript = \App\Models\ManuscriptRecord::factory()->filled()->create([
                'user_id' => $markUser->id,
            ]);

            $marksManuscript->manuscriptAuthors()->save(\App\Models\ManuscriptAuthor::create([
                'author_id' => $markAuthor->id,
                'is_corresponding_author' => true,
                'manuscript_record_id' => $marksManuscript->id,
                'organization_id' => $markAuthor->organization_id,
            ]));
        }
    }
}
