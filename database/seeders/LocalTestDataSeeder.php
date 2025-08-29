<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\PublicationAuthor;
use App\Models\Shareable;
use Illuminate\Database\Seeder;

class LocalTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->disableLogging();

        $this->call([
            ExpertisesTableSeeder::class,
            JournalsTableSeeder::class,
            DfoSeriesJournalSeeder::class,
        ]);

        // create an editor user
        \App\Models\User::factory()->editor()->create([
            'first_name' => 'Editor',
            'last_name' => 'User',
            'email' => 'editor@test.local',
        ]);

        // create an chief editor user
        \App\Models\User::factory()->chiefEditor()->create([
            'first_name' => 'Chief Editor',
            'last_name' => 'User',
            'email' => 'chief.editor@test.local',
        ]);

        // create a blank slate user
        \App\Models\User::factory()->create([
            'email' => 'new@test.local',
        ]);

        $user = \App\Models\User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'author@test.local',
        ]);

        // create 5 invitations for the test user
        \App\Models\Invitation::factory()->count(5)->create([
            'invited_by' => $user->id,
        ]);

        // create 1 manuscript records for the test user
        \App\Models\ManuscriptRecord::factory()
            ->has(ManuscriptAuthor::factory()->count(5))
            ->has(Shareable::factory()->state(['shared_by' => $user->id])->count(2))
            ->count(1)
            ->create([
                'title' => 'Shared Manuscript',
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

        // create 1 filled out secondary manuscript record for the test user
        \App\Models\ManuscriptRecord::factory()->secondary()->reviewed()->create([
            'user_id' => $user->id,
        ]);

        // create 1 manuscript record for the test user with a review step
        $toReview = \App\Models\ManuscriptRecord::factory()->in_review(false)->create([
            'user_id' => $user->id,
        ]);

        // create 1 secondary manuscript record for the test user with a review step
        $toReviewSec = \App\Models\ManuscriptRecord::factory()->secondary()->in_review(false)->create([
            'title' => 'Secondary Manuscript ready to review',
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
            'email' => 'dm@test.local',
        ]);

        // give the division manager a management review step
        ManagementReviewStep::factory()->create([
            'user_id' => $dmUser->id,
            'manuscript_record_id' => $toReview->id,
        ]);

        ManagementReviewStep::factory()->create([
            'user_id' => $dmUser->id,
            'manuscript_record_id' => $toReviewSec->id,
            'decision_expected_by' => null,
        ]);

        // create a rds user
        $rdsUser = \App\Models\User::factory()->create([
            'first_name' => 'RDS',
            'last_name' => 'User',
            'email' => 'rds@test.local',
        ]);
        $rdsUser->assignRole('director');

        // Make an author for Mark
        $markAuthor = \App\Models\Author::factory()->create([
            'first_name' => 'Mark',
            'last_name' => 'LaFlamme',
            'email' => 'mark.laflamme@dfo-mpo.gc.ca',
            'organization_id' => 1,
            'orcid' => 'https://orcid.org/0000-0001-5955-7098',
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

        $marksManuscript->manuscriptAuthors()->save(\App\Models\ManuscriptAuthor::factory()->create([
            'author_id' => $markAuthor->id,
            'is_corresponding_author' => true,
            'manuscript_record_id' => $marksManuscript->id,
            'organization_id' => $markAuthor->organization_id,
        ]));

        $adminUser = \App\Models\User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@test.local',
        ]);
        $adminUser->assignRole('admin');

        // add an announcement

        \App\Models\Announcement::factory()->create([
            'title_en' => 'Test Announcement',
            'title_fr' => 'Annonce de test',
            'text_en' => 'This is a test announcement.',
            'text_fr' => 'Ceci est une annonce de test.',
        ]);

        // let's add a few preprints
        ManuscriptRecord::factory()->publishedPreprint()->count(5)->create();

        // create regional editors for testing
        $nflEditor = \App\Models\User::factory()->create([
            'first_name' => 'NFL',
            'last_name' => 'Editor',
            'email' => 'nfl.editor@test.local',
        ]);
        $nflEditor->assignRole('nfl_editor');

        $marEditor = \App\Models\User::factory()->create([
            'first_name' => 'MAR',
            'last_name' => 'Editor',
            'email' => 'mar.editor@test.local',
        ]);
        $marEditor->assignRole('mar_editor');

        // create manuscripts for regional editor testing
        // NFL region (region_id = 1) - draft manuscript
        \App\Models\ManuscriptRecord::factory()->create([
            'status' => \App\Enums\ManuscriptRecordStatus::DRAFT,
            'region_id' => 1,
            'title' => 'NFL Draft Manuscript for Testing',
            'user_id' => $user->id,
        ]);

        // NFL region (region_id = 1) - in_review manuscript
        \App\Models\ManuscriptRecord::factory()->create([
            'status' => \App\Enums\ManuscriptRecordStatus::IN_REVIEW,
            'region_id' => 1,
            'title' => 'NFL In Review Manuscript for Testing',
            'user_id' => $user->id,
        ]);

        // MAR region (region_id = 2) - draft manuscript
        \App\Models\ManuscriptRecord::factory()->create([
            'status' => \App\Enums\ManuscriptRecordStatus::DRAFT,
            'region_id' => 2,
            'title' => 'MAR Draft Manuscript for Testing',
            'user_id' => $user->id,
        ]);

        // MAR region (region_id = 2) - in_review manuscript
        \App\Models\ManuscriptRecord::factory()->create([
            'status' => \App\Enums\ManuscriptRecordStatus::IN_REVIEW,
            'region_id' => 2,
            'title' => 'MAR In Review Manuscript for Testing',
            'user_id' => $user->id,
        ]);

        activity()->enableLogging();
    }
}
