<?php

use App\Enums\ManuscriptRecordType;
use App\Enums\PlanningBinder\PlanningBinderItemStatus;
use App\Events\PlanningBinder\FlaggedManuscriptAcceptedInJournal;
use App\Events\PlanningBinder\FlaggedManuscriptSubmittedToPrepint;
use App\Mail\PlanningBinder\FlaggedManuscriptAcceptedInJournalMail;
use App\Mail\PlanningBinder\FlaggedManuscriptOnPrepintServerMail;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Support\Facades\Mail;
use Thunk\Verbs\Facades\Verbs;

test('a flagged manuscript that is accepted to a journal triggers an event', function (): void {

    Verbs::fake();
    Mail::fake();

    $mrf = ManuscriptRecord::factory()->reviewed()->hasPlanningBinderItem()->create();

    $state = PlanningBinderItemState::factory()
        ->id($mrf->planningBinderItem->id->id())
        ->create(
            data: [
                'manuscript_record_type' => $mrf->type,
                'manuscript_record_ulid' => $mrf->ulid,
                'referrer_user_id' => $mrf->user_id,
            ]
        );

    $data = [
        'submitted_to_journal_on' => now()->subMonth()->toDateTimeString(),
        'accepted_on' => now()->toDateTimeString(),
        'journal_id' => Journal::factory()->create()->id,
    ];

    $response = $this->actingAs($mrf->user)->postJson('/api/manuscript-records/'.$mrf->id.'/accepted', $data);

    $response->assertStatus(200);

    Verbs::assertCommitted(FlaggedManuscriptAcceptedInJournal::class);
    Mail::assertQueued(FlaggedManuscriptAcceptedInJournalMail::class);

    expect($state->status)
        ->toBe(PlanningBinderItemStatus::READY);

    $mrf->refresh();
    expect($mrf->planningBinderItem)->toBeNull();
    expect($mrf->publication->planningBinderItem->status)
        ->toBe(PlanningBinderItemStatus::READY);

});

test('a flagged manuscript that is posted on a preprint server triggers an event', function (): void {

    Mail::fake();
    Verbs::fake();

    $mrf = ManuscriptRecord::factory()->reviewed()->hasPlanningBinderItem()->create();
    $mrf->type = ManuscriptRecordType::PREPRINT;
    $mrf->preprint_url = 'https://example.com/preprint-url';
    $mrf->save();

    $state = PlanningBinderItemState::factory()
        ->id($mrf->planningBinderItem->id->id())
        ->create(
            data: [
                'manuscript_record_type' => $mrf->type,
                'manuscript_record_ulid' => $mrf->ulid,
                'referrer_user_id' => $mrf->user_id,
            ]
        );

    $data = [
        'accepted_on' => now()->toDateTimeString(),
        'preprint_url' => 'https://example.com/preprint-url',
    ];

    $response = $this->actingAs($mrf->user)->putJson('/api/manuscript-records/'.$mrf->id.'/submitted-to-preprint', $data);

    $response->assertStatus(200);

    Verbs::assertCommitted(FlaggedManuscriptSubmittedToPrepint::class);
    Mail::assertQueued(FlaggedManuscriptOnPrepintServerMail::class);

    expect($state->status)
        ->toBe(PlanningBinderItemStatus::READY);

    $mrf->refresh();

    expect($mrf->planningBinderItem->status)
        ->toBe(PlanningBinderItemStatus::READY)
        ->and($mrf->preprint_url)
        ->toBe('https://example.com/preprint-url');

});
