<?php

use App\Events\PlanningBinder\FlaggedManuscriptAcceptedInJournal;
use App\Mail\PlanningBinder\FlaggedManuscriptAcceptedInJournalMail;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\States\PlanningBinder\PlanningBinderItemState;

test('a flagged manuscript that is accepted to a journal triggers an event', function () {

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

    $response = $this->actingAs($mrf->user)->putJson('/api/manuscript-records/'.$mrf->id.'/accepted', $data);

    $response->assertStatus(200);

    Verbs::assertCommitted(FlaggedManuscriptAcceptedInJournal::class);
    Mail::assertQueued(FlaggedManuscriptAcceptedInJournalMail::class);

    expect($state->status)
        ->toBe(\App\Enums\PlanningBinder\PlanningBinderItemStatus::READY);

    $mrf->refresh();
    expect($mrf->planningBinderItem)->toBeNull();
    expect($mrf->publication->planningBinderItem->status)
        ->toBe(\App\Enums\PlanningBinder\PlanningBinderItemStatus::READY);

});

test('a flagged manuscript that is posted on a preprint server triggers an event', function () {

    Mail::fake();
    Verbs::fake();

    $mrf = ManuscriptRecord::factory()->reviewed()->hasPlanningBinderItem()->create();
    $mrf->type = \App\Enums\ManuscriptRecordType::PREPRINT;
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

    Verbs::assertCommitted(\App\Events\PlanningBinder\FlaggedManuscriptSubmittedToPrepint::class);
    Mail::assertQueued(\App\Mail\PlanningBinder\FlaggedManuscriptOnPrepintServerMail::class);

    expect($state->status)
        ->toBe(\App\Enums\PlanningBinder\PlanningBinderItemStatus::READY);

    $mrf->refresh();

    expect($mrf->planningBinderItem->status)
        ->toBe(\App\Enums\PlanningBinder\PlanningBinderItemStatus::READY)
        ->and($mrf->preprint_url)
        ->toBe('https://example.com/preprint-url');

});
