<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptAuthor;
use App\Models\PublicationAuthor;
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
            // DfoSeriesJournalSeeder::class // enable this seeder when we go live
        ]);

        // here to test the system / demo - remove later
        // do not use in testing
        if (config('app.env') == 'local') {
            $this->call([
                JournalsTableSeeder::class,
            ]);

            // create a blank slate user
            \App\Models\User::factory()->create([
                'email' => 'new@vinnet.dev',
            ]);

            $user = \App\Models\User::factory()->create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'author@vinnet.dev',
            ]);

            // create 5 invitations for the test user
            \App\Models\Invitation::factory()->count(5)->create([
                'invited_by' => $user->id,
            ]);

            // create 1 manuscript records for the test user
            \App\Models\ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(5))->count(1)->create([
                'user_id' => $user->id,
            ]);

            // create 1 manuscript records that is accepted for the test user
            \App\Models\ManuscriptRecord::factory()->reviewed()->create([
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

            // create 2 publications without a manuscript record for the test user
            \App\Models\Publication::factory()->count(2)
                ->has(PublicationAuthor::factory([
                    'author_id' => $user->author->id,
                ]))->create([
                    'status' => PublicationStatus::PUBLISHED,
                    'user_id' => $user->id,
                    'journal_id' => \App\Models\Journal::first()->id,
                    'published_on' => now()->subMonths(10),
                    'accepted_on' => now()->subMonths(11),
                ]);

            // create a division manager user
            $dmUser = \App\Models\User::factory()->create([
                'first_name' => 'Division',
                'last_name' => 'Manager',
                'email' => 'dm@vinnet.dev',
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
                'email' => 'rds@vinnet.dev',
            ]);
            $rdsUser->assignRole('director');

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
