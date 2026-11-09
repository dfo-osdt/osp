<?php

use App\Enums\ManuscriptRecordType;
use App\Enums\OrcidSyncIntent;
use App\Enums\Permissions\UserRole;
use App\Filament\Resources\Journals\Pages\EditJournal;
use App\Jobs\SyncReviewContributionWithOrcid;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use App\Models\Publication;
use App\Models\User;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Requests\PeerReviews\DeletePeerReview;
use VincentAuger\OrcidSdk\Requests\PeerReviews\UpdatePeerReview;

/**
 * A contribution on its author's ORCID record with put-code 4567, to a published
 * report in a flagged DFO series
 *
 * @param  array<string, mixed>  $attributes
 */
function syncedReviewContribution(array $attributes = []): ManuscriptReviewContributor
{
    $manuscript = ManuscriptRecord::factory()->create(['type' => ManuscriptRecordType::SECONDARY]);

    Publication::factory()->published()->create([
        'title' => 'Stock assessment of Atlantic cod',
        'doi' => '10.60825/abc-123',
        'manuscript_record_id' => $manuscript->id,
        'journal_id' => Journal::factory()->dfoSeries()->create(['issn' => '1919-5079', 'orcid_sync_enabled' => true])->id,
    ]);

    $contributor = ManuscriptReviewContributor::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'author_id' => Author::factory()->withOrcidConnection()->create()->id,
    ]);
    $contributor->forceFill([
        'orcid_sync' => OrcidSyncIntent::WANTED,
        'orcid_putcode' => 4567,
        'orcid_updated_at' => now()->addMinute(),
        ...$attributes,
    ])->save();

    return $contributor;
}

function reviewContributorUrl(ManuscriptReviewContributor $contributor): string
{
    return "api/manuscript-records/{$contributor->manuscript_record_id}/review-contributors/{$contributor->id}";
}

test('removing a synced contributor deletes its ORCID item, then the contributor', function (): void {
    $mockClient = Saloon::fake([DeletePeerReview::class => MockResponse::make('', 204)]);
    $contributor = syncedReviewContribution();

    $this->actingAs(User::factory()->withRoles([UserRole::ADMIN])->create())
        ->deleteJson(reviewContributorUrl($contributor))
        ->assertNoContent();

    $mockClient->assertSent(fn (DeletePeerReview $request, $response): bool => str_ends_with($response->getPendingRequest()->getUrl(), '/peer-review/4567'));
    expect(ManuscriptReviewContributor::withTrashed()->find($contributor->id))->toBeNull();
});

test('changing a synced contributor\'s role updates its ORCID item', function (): void {
    $mockClient = Saloon::fake([UpdatePeerReview::class => MockResponse::make(['put-code' => 4567])]);
    $contributor = syncedReviewContribution();

    $this->actingAs(User::factory()->withRoles([UserRole::ADMIN])->create())
        ->putJson(reviewContributorUrl($contributor), ['role' => 'editor'])
        ->assertOk();

    $mockClient->assertSent(fn (UpdatePeerReview $request): bool => $request->body()->get('put-code') === 4567
        && $request->body()->get('reviewer-role') === 'editor');
    expect($contributor->refresh()->needsSyncWithOrcid())->toBeFalse();
});

test('changing a published publication\'s title or DOI updates its ORCID items', function (array $changes, Closure $sentChange): void {
    $mockClient = Saloon::fake([UpdatePeerReview::class => MockResponse::make(['put-code' => 4567])]);
    $contributor = syncedReviewContribution();
    $publication = $contributor->manuscriptRecord->publication;

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/publications/{$publication->id}", ['published_on' => $publication->published_on->toDateString(), ...$changes])
        ->assertOk();

    $mockClient->assertSent(fn (UpdatePeerReview $request): bool => $request->body()->get('put-code') === 4567 && $sentChange($request->body()->all()));
    expect($contributor->refresh()->needsSyncWithOrcid())->toBeFalse();
})->with([
    'title' => [['title' => 'Stock assessment of Atlantic cod in 2025'], fn (array $body): bool => $body['subject-name']['title'] === ['value' => 'Stock assessment of Atlantic cod in 2025']],
    'DOI' => [['doi' => 'https://doi.org/10.60825/def-456'], fn (array $body): bool => $body['subject-external-identifier']['external-id-url'] === ['value' => 'https://doi.org/10.60825/def-456']],
]);

test('a publication change that does not affect ORCID items leaves them alone', function (): void {
    $mockClient = Saloon::fake([]);
    $contributor = syncedReviewContribution();
    $publication = $contributor->manuscriptRecord->publication;

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/publications/{$publication->id}", ['published_on' => $publication->published_on->toDateString(), 'issue_number' => '42'])
        ->assertOk();

    $mockClient->assertNothingSent();
});

test('the ORCID item is deleted when its publication moves to a series that does not count, or loses its DOI', function (Closure $changes): void {
    $mockClient = Saloon::fake([DeletePeerReview::class => MockResponse::make('', 204)]);
    $contributor = syncedReviewContribution();
    $publication = $contributor->manuscriptRecord->publication;

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/publications/{$publication->id}", ['published_on' => $publication->published_on->toDateString(), ...$changes()])
        ->assertOk();

    $mockClient->assertSent(DeletePeerReview::class);
    $contributor->refresh();
    expect($contributor->orcid_putcode)->toBeNull()
        ->and($contributor->orcid_sync)->toBe(OrcidSyncIntent::WANTED);
})->with([
    'journal not flagged' => [fn (): array => ['journal_id' => Journal::factory()->dfoSeries()->create(['issn' => '1919-5079'])->id]],
    'DOI removed' => [fn (): array => ['doi' => null]],
]);

test('turning off ORCID sync for a journal deletes the ORCID items of its publications once confirmed', function (array $changes): void {
    $mockClient = Saloon::fake([DeletePeerReview::class => MockResponse::make('', 204)]);
    $contributor = syncedReviewContribution();
    $journal = $contributor->manuscriptRecord->publication->journal;
    $admin = User::factory()->withRoles([UserRole::ADMIN])->create();

    $page = $this->actingAs($admin)
        ->livewire(EditJournal::class, ['record' => $journal->getRouteKey()])
        ->fillForm($changes)
        ->call('save')
        ->assertActionMounted('turnOffOrcidSync');

    $mockClient->assertNothingSent();
    expect($journal->fresh()->orcid_sync_enabled)->toBeTrue();

    $page->callMountedAction()->assertHasNoFormErrors();

    $mockClient->assertSent(DeletePeerReview::class);
    expect($journal->fresh()->orcid_sync_enabled)->toBeFalse()
        ->and($contributor->refresh()->orcid_putcode)->toBeNull();
    $activity = Activity::query()->where('description', 'journal.orcid-sync-turned-off')->sole();
    expect($activity->subject->is($journal))->toBeTrue()
        ->and($activity->causer->is($admin))->toBeTrue()
        ->and($activity->properties['contributions_on_orcid'])->toBe(1);
})->with([
    'flag turned off' => [['orcid_sync_enabled' => false]],
    'no longer a DFO series' => [['publisher' => 'NRC Press']],
]);

test('turning off ORCID sync for a journal without ORCID items needs no confirmation', function (): void {
    $contributor = syncedReviewContribution(['orcid_putcode' => null, 'orcid_updated_at' => null]);
    $journal = $contributor->manuscriptRecord->publication->journal;

    $this->actingAs(User::factory()->withRoles([UserRole::ADMIN])->create())
        ->livewire(EditJournal::class, ['record' => $journal->getRouteKey()])
        ->fillForm(['orcid_sync_enabled' => false])
        ->call('save')
        ->assertActionNotMounted('turnOffOrcidSync')
        ->assertHasNoFormErrors();

    expect($journal->fresh()->orcid_sync_enabled)->toBeFalse();
});

test('removing a contributor that was never on ORCID does not call ORCID', function (): void {
    $mockClient = Saloon::fake([]);
    $contributor = syncedReviewContribution(['orcid_sync' => null, 'orcid_putcode' => null, 'orcid_updated_at' => null]);

    $this->actingAs(User::factory()->withRoles([UserRole::ADMIN])->create())
        ->deleteJson(reviewContributorUrl($contributor))
        ->assertNoContent();

    $mockClient->assertNothingSent();
    expect(ManuscriptReviewContributor::withTrashed()->find($contributor->id))->toBeNull();
});

test('a sync still queued for a contributor removed for good is dropped', function (): void {
    $mockClient = Saloon::fake([]);
    $contributor = syncedReviewContribution(['orcid_putcode' => null, 'orcid_updated_at' => null]);
    $queuedJob = new SyncReviewContributionWithOrcid($contributor);
    $contributor->forceDelete();

    dispatch($queuedJob);

    $mockClient->assertNothingSent();
});

test('a removed contributor waiting for its ORCID removal can be added again', function (): void {
    $mockClient = Saloon::fake([]);
    $contributor = syncedReviewContribution();
    $contributor->author->clearOrcidToken();
    $admin = User::factory()->withRoles([UserRole::ADMIN])->create();
    $this->actingAs($admin)->deleteJson(reviewContributorUrl($contributor))->assertNoContent();

    $this->actingAs($admin)->getJson("api/manuscript-records/{$contributor->manuscript_record_id}/review-contributors")
        ->assertOk()
        ->assertJsonCount(0, 'data');
    $this->actingAs($admin)->postJson("api/manuscript-records/{$contributor->manuscript_record_id}/review-contributors", [
        'author_id' => $contributor->author_id,
        'role' => 'reviewer',
    ])->assertCreated();

    $mockClient->assertNothingSent();
    expect($contributor->refresh()->trashed())->toBeTrue()
        ->and($contributor->orcid_putcode)->toBe(4567);
});
