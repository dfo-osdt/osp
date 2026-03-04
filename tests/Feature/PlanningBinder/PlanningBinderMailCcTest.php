<?php

use App\Enums\ManagementReviewStepStatus;
use App\Mail\PlanningBinder\FlaggedManuscriptAcceptedInJournalMail;
use App\Mail\PlanningBinder\FlaggedManuscriptOnPrepintServerMail;
use App\Mail\PlanningBinder\ManuscriptFlaggedForPlanningBinderMail;
use App\Models\ManuscriptRecord;
use App\Models\NotificationGroupMember;
use App\Models\Publication;
use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Mail\Mailables\Address;

function ccEmails(\Illuminate\Mail\Mailables\Envelope $envelope): array
{
    return collect($envelope->cc)->map(fn (Address $a): string => $a->address)->all();
}

test('ManuscriptFlaggedForPlanningBinderMail CCs completed reviewers and their notification groups', function (): void {

    $mrf = ManuscriptRecord::factory()->reviewed()->hasPlanningBinderItem()->create();

    $completedReviewer = $mrf->managementReviewSteps()
        ->where('status', ManagementReviewStepStatus::COMPLETED)
        ->first()
        ->user;

    $notificationGroupMember = NotificationGroupMember::factory()->create([
        'user_id' => $completedReviewer->id,
    ]);

    $state = PlanningBinderItemState::factory()
        ->id($mrf->planningBinderItem->id->id())
        ->create(data: [
            'manuscript_record_type' => $mrf->type,
            'manuscript_record_ulid' => $mrf->ulid,
            'referrer_user_id' => $mrf->user_id,
        ]);

    $mail = new ManuscriptFlaggedForPlanningBinderMail($mrf->user, $state);
    $cc = ccEmails($mail->envelope());

    expect($cc)
        ->toContain(config('osp.manuscript_submission_email'))
        ->toContain($completedReviewer->email)
        ->toContain($notificationGroupMember->member->email);
});

test('FlaggedManuscriptOnPrepintServerMail CCs completed reviewers and their notification groups', function (): void {

    $mrf = ManuscriptRecord::factory()->reviewed()->hasPlanningBinderItem()->create();

    $completedReviewer = $mrf->managementReviewSteps()
        ->where('status', ManagementReviewStepStatus::COMPLETED)
        ->first()
        ->user;

    $notificationGroupMember = NotificationGroupMember::factory()->create([
        'user_id' => $completedReviewer->id,
    ]);

    $state = PlanningBinderItemState::factory()
        ->id($mrf->planningBinderItem->id->id())
        ->create(data: [
            'manuscript_record_type' => $mrf->type,
            'manuscript_record_ulid' => $mrf->ulid,
            'referrer_user_id' => $mrf->user_id,
        ]);

    $mail = new FlaggedManuscriptOnPrepintServerMail($state);
    $cc = ccEmails($mail->envelope());

    expect($cc)
        ->toContain(config('osp.manuscript_submission_email'))
        ->toContain($completedReviewer->email)
        ->toContain($notificationGroupMember->member->email);
});

test('FlaggedManuscriptAcceptedInJournalMail CCs completed reviewers and their notification groups', function (): void {

    $mrf = ManuscriptRecord::factory()->reviewed()->hasPlanningBinderItem()->create();

    $completedReviewer = $mrf->managementReviewSteps()
        ->where('status', ManagementReviewStepStatus::COMPLETED)
        ->first()
        ->user;

    $notificationGroupMember = NotificationGroupMember::factory()->create([
        'user_id' => $completedReviewer->id,
    ]);

    $publication = Publication::factory()->create([
        'manuscript_record_id' => $mrf->id,
    ]);

    $state = PlanningBinderItemState::factory()
        ->id($mrf->planningBinderItem->id->id())
        ->create(data: [
            'manuscript_record_type' => $mrf->type,
            'manuscript_record_ulid' => $mrf->ulid,
            'referrer_user_id' => $mrf->user_id,
            'publication_id' => $publication->id,
        ]);

    $mail = new FlaggedManuscriptAcceptedInJournalMail($state);
    $cc = ccEmails($mail->envelope());

    expect($cc)
        ->toContain(config('osp.manuscript_submission_email'))
        ->toContain($completedReviewer->email)
        ->toContain($notificationGroupMember->member->email);
});

test('planning binder mails do not CC the TO recipient', function (): void {

    $mrf = ManuscriptRecord::factory()->reviewed()->hasPlanningBinderItem()->create();

    $mrf->managementReviewSteps()->update(['user_id' => $mrf->user_id]);

    $state = PlanningBinderItemState::factory()
        ->id($mrf->planningBinderItem->id->id())
        ->create(data: [
            'manuscript_record_type' => $mrf->type,
            'manuscript_record_ulid' => $mrf->ulid,
            'referrer_user_id' => $mrf->user_id,
        ]);

    $mail = new ManuscriptFlaggedForPlanningBinderMail($mrf->user, $state);
    $cc = ccEmails($mail->envelope());

    expect($cc)->not->toContain($mrf->user->email);
});
