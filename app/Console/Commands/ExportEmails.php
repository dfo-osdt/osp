<?php

namespace App\Console\Commands;

use App\Enums\ManuscriptRecordType;
use App\Events\Auth\Invited;
use App\Mail\ManagementReviewDueMail;
use App\Mail\ManagementReviewPendingMail;
use App\Mail\ManuscriptRecordSubmittedToDFO;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\Publication;
use App\Models\Shareable;
use App\Models\User;
use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Console\Command;

class ExportEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osp:export-emails {--output=storage/exports/markdown}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export emails templates to the specified directory. This command should only be used in a local/dev environment.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! app()->environment('local')) {
            $this->error('This command is only available in the local environment.');

            return 1;
        }

        // start a database transaction, we will rollback after this to leave the database clean
        \DB::beginTransaction();

        $mrfReviewComplete = new \App\Mail\ManuscriptManagementReviewComplete(ManuscriptRecord::factory([
            'type' => ManuscriptRecordType::PRIMARY,
        ])->reviewed()->create());
        $markdownContent = $mrfReviewComplete->render();
        $this->exportFile('mrf-primary-review-complete.html', $markdownContent);

        $mrfReviewComplete = new \App\Mail\ManuscriptManagementReviewComplete(ManuscriptRecord::factory([
            'type' => ManuscriptRecordType::SECONDARY,
        ])->reviewed()->create());
        $markdownContent = $mrfReviewComplete->render();
        $this->exportFile('mrf-secondary-review-complete.html', $markdownContent);

        $manuscriptRecordShared = new \App\Mail\ManuscriptRecordSharedMail(Shareable::factory()->create());
        $markdownContent = $manuscriptRecordShared->render();
        $this->exportFile('manuscript-record-shared.html', $markdownContent);

        $pub = Publication::factory()->withManuscript()->create();
        $manuscriptRecordSubmittedToDFO = new ManuscriptRecordSubmittedToDFO($pub->manuscriptRecord);
        $markdownContent = $manuscriptRecordSubmittedToDFO->render();
        $this->exportFile('manuscript-record-submitted-to-dfo.html', $markdownContent);

        $manuscriptRecordToReview = new \App\Mail\ManuscriptRecordToReviewMail(ManuscriptRecord::factory()->in_review()->create(), User::factory()->create());
        $markdownContent = $manuscriptRecordToReview->render();
        $this->exportFile('manuscript-record-to-review-external.html', $markdownContent);

        $manuscriptRecordToReview = new \App\Mail\ManuscriptRecordToReviewMail(ManuscriptRecord::factory()->secondary()->in_review(true, true)->create(), User::factory()->create());
        $markdownContent = $manuscriptRecordToReview->render();
        $this->exportFile('manuscript-record-to-review-internal.html', $markdownContent);

        // Next reviwer: flagged - return to author
        $mrf = ManagementReviewStep::factory([
            'status' => \App\Enums\ManagementReviewStepStatus::COMPLETED,
            'decision' => \App\Enums\ManagementReviewStepDecision::REVISION,
            'comments' => 'This manuscript is not ready for publication, please change X, Y and Z',
        ])->create()->manuscriptRecord;
        $step = $mrf->managementReviewSteps()->save(ManagementReviewStep::factory()->create([
            'status' => \App\Enums\ManagementReviewStepStatus::ON_HOLD,
            'previous_step_id' => $mrf->managementReviewSteps()->first()->id,
        ]));

        $reviewStepNotification = new \App\Mail\ReviewStepNotificationMail($step);
        $markdownContent = $reviewStepNotification->render();
        $this->exportFile('review-step-notification-revision.html', $markdownContent);

        // Next reviwer: approved - forward to another reviewer
        $mrf = ManagementReviewStep::factory([
            'status' => \App\Enums\ManagementReviewStepStatus::COMPLETED,
            'decision' => \App\Enums\ManagementReviewStepDecision::COMPLETE,
            'comments' => 'I reviewed this manuscript and it is ready for publication',
        ])->create()->manuscriptRecord;
        $step = $mrf->managementReviewSteps()->save(ManagementReviewStep::factory()->create([
            'previous_step_id' => $mrf->managementReviewSteps()->first()->id,
        ]));
        $reviewStepNotification = new \App\Mail\ReviewStepNotificationMail($step);
        $markdownContent = $reviewStepNotification->render();
        $this->exportFile('review-step-notification-primary-refer.html', $markdownContent);

        // next reviewer: secondary manuscript
        $mrf = ManagementReviewStep::factory([
            'status' => \App\Enums\ManagementReviewStepStatus::COMPLETED,
            'decision' => \App\Enums\ManagementReviewStepDecision::COMPLETE,
            'comments' => 'I reviewed this manuscript and it is ready for publication',
        ])->has(ManuscriptRecord::factory()->secondary())->create()->manuscriptRecord;
        $step = $mrf->managementReviewSteps()->save(ManagementReviewStep::factory()->create([
            'previous_step_id' => $mrf->managementReviewSteps()->first()->id,
            'decision_expected_by' => null,
        ]));
        $reviewStepNotification = new \App\Mail\ReviewStepNotificationMail($step);
        $markdownContent = $reviewStepNotification->render();
        $this->exportFile('review-step-notification-secondary-refer.html', $markdownContent);

        // user invited email
        $invitation = \App\Models\Invitation::factory()->create();
        $invitedEvent = new Invited(
            $invitation,
            'temporary-password',
        );
        $userInvited = new \App\Mail\UserInvitedMail($invitedEvent);
        $markdownContent = $userInvited->render();
        $this->exportFile('user-invited.html', $markdownContent);

        $userInvitedWelcome = new \App\Mail\UserInvitedWelomeMail($invitedEvent);
        $markdownContent = $userInvitedWelcome->render();
        $this->exportFile('user-invited-welcome.html', $markdownContent);

        // item flagged for planning binder - mrf
        $mrf = ManagementReviewStep::factory([
            'status' => \App\Enums\ManagementReviewStepStatus::COMPLETED,
            'decision' => \App\Enums\ManagementReviewStepDecision::COMPLETE,
            'comments' => 'I reviewed this manuscript and it is ready for publication',
        ])->create()->manuscriptRecord;
        $mrf->title = 'A important manuscript the ADM should know about';
        $mrf->save();

        $user = $mrf->managementReviewSteps()->first()->user;

        $state = PlanningBinderItemState::factory()->state([
            'manuscript_record_ulid' => $mrf->ulid,
            'manuscript_record_type' => $mrf->type,
            'referrer_user_id' => $mrf->managementReviewSteps()->first()->user->id,
        ])->create();
        $flaggedEmail = new \App\Mail\PlanningBinder\ManuscriptFlaggedForPlanningBinderMail($user, $state);
        $markdownContent = $flaggedEmail->render();
        $this->exportFile('item-flagged-for-planning-binder-mrf.html', $markdownContent);

        // flagged item for planning binder - preprint
        $mrf = ManagementReviewStep::factory([
            'status' => \App\Enums\ManagementReviewStepStatus::COMPLETED,
            'decision' => \App\Enums\ManagementReviewStepDecision::COMPLETE,
            'comments' => 'I reviewed this manuscript and it is ready for publication',
        ])->create()->manuscriptRecord;
        $mrf->title = 'A important preprint the ADM should know about';
        $mrf->type = \App\Enums\ManuscriptRecordType::PREPRINT;
        $mrf->preprint_url = 'https://example.com/preprint-url';
        $mrf->save();

        $state = PlanningBinderItemState::factory()->state([
            'manuscript_record_ulid' => $mrf->ulid,
            'manuscript_record_type' => $mrf->type,
            'referrer_user_id' => $mrf->managementReviewSteps()->first()->user->id,
        ])->create();

        $flaggedEmail = new \App\Mail\PlanningBinder\FlaggedManuscriptOnPrepintServerMail($state);
        $markdownContent = $flaggedEmail->render();
        $this->exportFile('flagged-manuscript-on-preprint-server.html', $markdownContent);

        $publication = Publication::factory()->withManuscript()->create();
        $state = PlanningBinderItemState::factory()->state([
            'manuscript_record_ulid' => $publication->manuscriptRecord->ulid,
            'manuscript_record_type' => $publication->manuscriptRecord->type,
            'publication_id' => $publication->id,
            'referrer_user_id' => User::factory()->create()->id,
        ])->create();

        $flaggedEmail = new \App\Mail\PlanningBinder\FlaggedManuscriptAcceptedInJournalMail($state);
        $markdownContent = $flaggedEmail->render();
        $this->exportFile('flagged-manuscript-accepted-in-journal.html', $markdownContent);

        // Management review due soon (only due soon reviews)
        $user = User::factory()->create();
        $dueSoonReviews = collect([
            ManagementReviewStep::factory()->create([
                'user_id' => $user->id,
                'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
                'decision_expected_by' => now()->addDay(),
            ]),
            ManagementReviewStep::factory()->create([
                'user_id' => $user->id,
                'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
                'decision_expected_by' => now()->addBusinessDays(2),
            ]),
            ManagementReviewStep::factory()->create([
                'user_id' => $user->id,
                'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
                'decision_expected_by' => now()->addHours(12),
            ]),
        ])->each(fn ($review) => $review->load('manuscriptRecord'));

        $managementReviewDueSoon = new ManagementReviewDueMail($dueSoonReviews, $user);
        $markdownContent = $managementReviewDueSoon->render();
        $this->exportFile('management-review-due-soon.html', $markdownContent);

        // Management review due and overdue (mix of both)
        $user = User::factory()->create();
        $mixedReviews = collect([
            ManagementReviewStep::factory()->create([
                'user_id' => $user->id,
                'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
                'decision_expected_by' => now()->subDays(3),
            ]),
            ManagementReviewStep::factory()->create([
                'user_id' => $user->id,
                'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
                'decision_expected_by' => now()->subDay(),
            ]),
            ManagementReviewStep::factory()->create([
                'user_id' => $user->id,
                'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
                'decision_expected_by' => now()->addDay(),
            ]),
            ManagementReviewStep::factory()->create([
                'user_id' => $user->id,
                'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
                'decision_expected_by' => now()->addBusinessDays(2),
            ]),
        ])->each(fn ($review) => $review->load('manuscriptRecord'));

        $managementReviewDueAndOverdue = new ManagementReviewDueMail($mixedReviews, $user);
        $markdownContent = $managementReviewDueAndOverdue->render();
        $this->exportFile('management-review-due-and-overdue.html', $markdownContent);

        // Management review weekly pending summary
        $user = User::factory()->create();
        $pendingReviews = collect([
            ManagementReviewStep::factory()->create([
                'user_id' => $user->id,
                'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
                'created_at' => now()->subBusinessDays(10),
                'decision_expected_by' => now()->addBusinessDays(5),
            ]),
            ManagementReviewStep::factory()->create([
                'user_id' => $user->id,
                'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
                'created_at' => now()->subBusinessDays(5),
                'decision_expected_by' => now()->addBusinessDays(3),
            ]),
            ManagementReviewStep::factory()->create([
                'user_id' => $user->id,
                'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
                'created_at' => now()->subBusinessDays(2),
                'decision_expected_by' => now()->addBusinessDay(),
            ]),
        ])->each(fn ($review) => $review->load('manuscriptRecord'));

        $managementReviewPending = new ManagementReviewPendingMail($pendingReviews, $user);
        $markdownContent = $managementReviewPending->render();
        $this->exportFile('management-review-pending-weekly.html', $markdownContent);

        \DB::rollBack();

        return 0;
    }

    /** Helper that exports the file */
    private function exportFile($fileName, $content)
    {
        $outputPath = $this->option('output');
        $outputFile = $outputPath.'/'.$fileName;
        if (! is_dir($outputPath)) {
            mkdir($outputPath, 0755, true);
        }

        file_put_contents($outputFile, $content);
        $this->info('Exported email template to '.$outputFile);
    }
}
