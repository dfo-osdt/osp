<?php

namespace Database\Seeders;

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Enums\ManuscriptRecordStatus;
use App\Enums\PublicationStatus;
use App\Models\Announcement;
use App\Models\Author;
use App\Models\HelpfulLink;
use App\Models\Invitation;
use App\Models\Journal;
use App\Models\ManagementReviewDelegation;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\Region;
use App\Models\Shareable;
use App\Models\User;
use Illuminate\Database\Seeder;

class LocalTestDataSeeder extends Seeder
{
    public function run(): void
    {
        activity()->disableLogging();

        // ── Reference data ────────────────────────────────────────────────────

        $this->call([
            ExpertisesTableSeeder::class,
            JournalsTableSeeder::class,
            DfoSeriesJournalSeeder::class,
        ]);

        $nflRegion = Region::where('slug', 'nfl')->first();
        $nflRegion->enforce_secondary_review_deadline = true;
        $nflRegion->save();

        // ── Users ─────────────────────────────────────────────────────────────

        User::factory()->editor()->create([
            'first_name' => 'Editor',
            'last_name' => 'User',
            'email' => 'editor@test.local',
        ]);

        User::factory()->chiefEditor()->create([
            'first_name' => 'Chief Editor',
            'last_name' => 'User',
            'email' => 'chief.editor@test.local',
        ]);

        $nflEditor = User::factory()->create([
            'first_name' => 'NFL',
            'last_name' => 'Editor',
            'email' => 'nfl.editor@test.local',
        ]);
        $nflEditor->assignRole('nfl_editor');

        $marEditor = User::factory()->create([
            'first_name' => 'MAR',
            'last_name' => 'Editor',
            'email' => 'mar.editor@test.local',
        ]);
        $marEditor->assignRole('mar_editor');

        $nflObserver = User::factory()->create([
            'first_name' => 'NFL',
            'last_name' => 'Observer',
            'email' => 'nfl.observer@test.local',
        ]);
        $nflObserver->assignRole('nfl_observer');

        User::factory()->create([
            'email' => 'new@test.local',
        ]);

        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'author@test.local',
        ]);

        $dmUser = User::factory()->create([
            'first_name' => 'Division',
            'last_name' => 'Manager',
            'email' => 'dm@test.local',
        ]);

        $rdsUser = User::factory()->create([
            'first_name' => 'RDS',
            'last_name' => 'User',
            'email' => 'rds@test.local',
        ]);
        $rdsUser->assignRole('director');

        $markAuthor = Author::factory()->create([
            'first_name' => 'Mark',
            'last_name' => 'LaFlamme',
            'email' => 'mark.laflamme@dfo-mpo.gc.ca',
            'organization_id' => 1,
            'orcid' => 'https://orcid.org/0000-0001-5955-7098',
        ]);

        $markUser = User::factory()->create([
            'first_name' => 'Mark',
            'last_name' => 'LaFlamme',
            'email' => 'mark.laflamme@dfo-mpo.gc.ca',
        ]);

        $adminUser = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@test.local',
        ]);
        $adminUser->assignRole('admin');

        // ── Author user manuscripts ───────────────────────────────────────────

        Invitation::factory()->count(5)->create([
            'invited_by' => $user->id,
        ]);

        ManuscriptRecord::factory()
            ->has(ManuscriptAuthor::factory()->count(5))
            ->has(Shareable::factory()->state(['shared_by' => $user->id])->count(2))
            ->create([
                'title' => 'Shared Manuscript',
                'user_id' => $user->id,
            ]);

        ManuscriptRecord::factory()->reviewed()->create([
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->filled()->create([
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->secondary()->reviewed()->create([
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->secondary()->filled()->create([
            'title' => 'NFL Secondary - Ready to Submit (10-day deadline enforced)',
            'user_id' => $user->id,
            'region_id' => $nflRegion->id,
        ]);

        $toReview = ManuscriptRecord::factory()->in_review(false)->create([
            'user_id' => $user->id,
        ]);

        $toReviewSec = ManuscriptRecord::factory()->secondary()->in_review(false)->create([
            'title' => 'Secondary Manuscript ready to review',
            'user_id' => $user->id,
        ]);

        // ── Publications ──────────────────────────────────────────────────────

        Publication::factory()->count(2)
            ->has(PublicationAuthor::factory([
                'author_id' => $user->author->id,
            ]))->create([
                'status' => PublicationStatus::PUBLISHED,
                'user_id' => $user->id,
                'journal_id' => Journal::first()->id,
                'published_on' => now()->subMonths(10),
                'accepted_on' => now()->subMonths(11),
            ]);

        Publication::factory()->count(2)
            ->has(PublicationAuthor::factory(3))
            ->create([
                'status' => PublicationStatus::PUBLISHED,
                'user_id' => $user->id,
                'journal_id' => Journal::first()->id,
                'published_on' => now()->subMonths(10),
                'accepted_on' => now()->subMonths(11),
            ]);

        // ── Management review steps ───────────────────────────────────────────

        ManagementReviewStep::factory()->create([
            'user_id' => $dmUser->id,
            'manuscript_record_id' => $toReview->id,
        ]);

        ManagementReviewStep::factory()->create([
            'user_id' => $dmUser->id,
            'manuscript_record_id' => $toReviewSec->id,
            'decision_expected_by' => null,
        ]);

        $overdueManuscript = ManuscriptRecord::factory()->in_review(false)->create([
            'title' => 'Overdue Manuscript - 15 days in review',
            'user_id' => $user->id,
            'sent_for_review_at' => now()->subDays(15),
        ]);

        ManagementReviewStep::factory()->create([
            'user_id' => $dmUser->id,
            'manuscript_record_id' => $overdueManuscript->id,
            'decision_expected_by' => now()->subDays(5),
        ]);

        // ── Mark LaFlamme ─────────────────────────────────────────────────────

        $marksManuscript = ManuscriptRecord::factory()->filled()->create([
            'user_id' => $markUser->id,
        ]);

        $marksManuscript->manuscriptAuthors()->save(ManuscriptAuthor::factory()->create([
            'author_id' => $markAuthor->id,
            'is_corresponding_author' => true,
            'manuscript_record_id' => $marksManuscript->id,
            'organization_id' => $markAuthor->organization_id,
        ]));

        // ── Forward-to-delegate demo ──────────────────────────────────────────
        // DM reviewed and referred to RDS; RDS went on leave without setting up a delegation.
        // Admin added RDS→DM delegation via librarium after the fact.
        // Admin can now click "Forward to Delegate" on RDS's pending step.

        $forwardDemoManuscript = ManuscriptRecord::factory()->secondary()->in_review(false)->create([
            'title' => 'Forward-to-Delegate Demo (RDS pending, delegation set post-hoc)',
            'user_id' => $user->id,
        ]);

        $dmReferStep = ManagementReviewStep::factory()->create([
            'manuscript_record_id' => $forwardDemoManuscript->id,
            'user_id' => $dmUser->id,
            'status' => ManagementReviewStepStatus::COMPLETED,
            'decision' => ManagementReviewStepDecision::COMPLETE,
            'completed_at' => now()->subDays(2),
            'comments' => 'Reviewed — forwarding to RDS for final approval.',
        ]);

        // Step created BEFORE the delegation so the observer does not auto-reassign it
        ManagementReviewStep::factory()->create([
            'manuscript_record_id' => $forwardDemoManuscript->id,
            'user_id' => $rdsUser->id,
            'status' => ManagementReviewStepStatus::PENDING,
            'previous_step_id' => $dmReferStep->id,
            'decision_expected_by' => now()->addDays(3),
        ]);

        ManagementReviewDelegation::factory()->create([
            'user_id' => $rdsUser->id,
            'delegate_user_id' => $dmUser->id,
            'comment' => 'RDS is on leave — please handle reviews on my behalf.',
        ]);

        // ── Regional manuscripts (NFL / MAR) ──────────────────────────────────

        ManuscriptRecord::factory()->create([
            'title' => 'NFL Draft Manuscript',
            'status' => ManuscriptRecordStatus::DRAFT,
            'region_id' => $nflRegion->id,
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->create([
            'title' => 'MAR Draft Manuscript',
            'status' => ManuscriptRecordStatus::DRAFT,
            'region_id' => 2,
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->in_review()->create([
            'title' => 'NFL In Review Manuscript',
            'region_id' => $nflRegion->id,
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->in_review()->create([
            'title' => 'MAR In Review Manuscript',
            'region_id' => 2,
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->reviewed()->create([
            'title' => 'NFL Reviewed Manuscript',
            'region_id' => $nflRegion->id,
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->reviewed()->create([
            'title' => 'MAR Reviewed Manuscript',
            'region_id' => 2,
            'user_id' => $user->id,
        ]);

        // ── Miscellaneous ─────────────────────────────────────────────────────

        ManuscriptRecord::factory()->publishedPreprint()->count(5)->create();

        HelpfulLink::factory()->count(3)->create();

        Announcement::factory()->create([
            'title_en' => 'Test Announcement',
            'title_fr' => 'Annonce de test',
            'text_en' => 'This is a test announcement.',
            'text_fr' => 'Ceci est une annonce de test.',
        ]);

        activity()->enableLogging();
    }
}
