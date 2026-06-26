<?php

namespace Database\Seeders;

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Enums\PlanningBinder\PlanningBinderItemStatus;
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
use App\Models\PlanningBinderItem;
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

        $marRegion = Region::where('slug', 'mar')->first();

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

        // ── Editor dashboard: EOS (secondary) pipeline ────────────────────────
        // Secondary MRFs approved by management (status = reviewed) but not yet
        // submitted to Science Publications → "Awaiting Science Publications count.
        $awaitingSubmission = [
            ['title' => 'EOS Coastal Habitat Assessment — awaiting submission', 'region_id' => $nflRegion->id],
            ['title' => 'EOS Atlantic Salmon Stock Review — awaiting submission', 'region_id' => $marRegion->id],
            ['title' => 'EOS Snow Crab Survey — awaiting submission', 'region_id' => $nflRegion->id],
            ['title' => 'EOS Eelgrass Mapping — awaiting submission', 'region_id' => $marRegion->id],
            ['title' => 'EOS Marine Protected Area Monitoring — awaiting submission', 'region_id' => $nflRegion->id],
        ];

        foreach ($awaitingSubmission as $row) {
            ManuscriptRecord::factory()->secondary()->reviewed()->create([
                'title' => $row['title'],
                'region_id' => $row['region_id'],
                'user_id' => $user->id,
            ]);
        }

        // Secondary publications accepted by Science Publications awaiting publication
        // → the editor "due queue" (oldest first). Some are flagged for the
        // planning binder, which re-associates the binder item to the publication.
        $dfoJournal = Journal::query()->dfoSeries()->first();

        $dueQueue = [
            ['title' => 'EOS Cod Recovery Strategy', 'catalogue_number' => 'Fs97-18/401E-PDF', 'days_ago' => 96, 'region_id' => $nflRegion->id, 'flagged' => true],
            ['title' => 'EOS Lobster Settlement Index super long title that we could see at any time in one of our reports', 'catalogue_number' => 'Fs97-18/402E-PDF', 'days_ago' => 74, 'region_id' => $marRegion->id, 'flagged' => false],
            ['title' => 'EOS Capelin Spawning Forecast', 'catalogue_number' => 'Fs97-18/403E-PDF', 'days_ago' => 58, 'region_id' => $nflRegion->id, 'flagged' => true],
            ['title' => 'EOS Right Whale Distribution', 'catalogue_number' => 'Fs97-18/404E-PDF', 'days_ago' => 41, 'region_id' => $marRegion->id, 'flagged' => true],
            ['title' => 'EOS Herring Acoustic Survey', 'catalogue_number' => 'Fs97-18/405E-PDF', 'days_ago' => 33, 'region_id' => $nflRegion->id, 'flagged' => false],
            ['title' => 'EOS Scallop Biomass Update', 'catalogue_number' => 'Fs97-18/406E-PDF', 'days_ago' => 19, 'region_id' => $marRegion->id, 'flagged' => false],
            ['title' => 'EOS Seal Population Census', 'catalogue_number' => 'Fs97-18/407E-PDF', 'days_ago' => 8, 'region_id' => $nflRegion->id, 'flagged' => false],
        ];

        foreach ($dueQueue as $row) {
            $publication = Publication::factory()->create([
                'title' => $row['title'],
                'catalogue_number' => $row['catalogue_number'],
                'status' => PublicationStatus::ACCEPTED,
                'user_id' => $user->id,
                'journal_id' => $dfoJournal->id,
                'region_id' => $row['region_id'],
                'accepted_on' => now()->subDays($row['days_ago']),
                'published_on' => null,
            ]);

            PublicationAuthor::factory()->create([
                'publication_id' => $publication->id,
                'is_corresponding_author' => true,
            ]);

            PublicationAuthor::factory()->create([
                'publication_id' => $publication->id,
                'is_corresponding_author' => false,
            ]);

            if ($row['flagged']) {
                PlanningBinderItem::factory()->create([
                    'plannable_type' => Publication::class,
                    'plannable_id' => $publication->id,
                    'type' => ManuscriptRecordType::SECONDARY,
                    'status' => PlanningBinderItemStatus::READY,
                    'region_id' => $row['region_id'],
                ]);
            }
        }

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
            'region_id' => $marRegion->id,
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->in_review()->create([
            'title' => 'NFL In Review Manuscript',
            'region_id' => $nflRegion->id,
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->in_review()->create([
            'title' => 'MAR In Review Manuscript',
            'region_id' => $marRegion->id,
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->reviewed()->create([
            'title' => 'NFL Reviewed Manuscript',
            'region_id' => $nflRegion->id,
            'user_id' => $user->id,
        ]);

        ManuscriptRecord::factory()->reviewed()->create([
            'title' => 'MAR Reviewed Manuscript',
            'region_id' => $marRegion->id,
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
