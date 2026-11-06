<?php

use App\Enums\ManuscriptRecordType;
use App\Enums\OrcidSyncIntent;
use App\Jobs\SyncReviewContributionWithOrcid;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use App\Models\Organization;
use App\Models\Publication;
use App\Orcid\OrcidClientFactory;
use Illuminate\Support\Facades\Queue;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Exceptions\OrcidServerException;
use VincentAuger\OrcidSdk\Requests\PeerReviews\CreatePeerReview;
use VincentAuger\OrcidSdk\Requests\PeerReviews\DeletePeerReview;
use VincentAuger\OrcidSdk\Requests\PeerReviews\UpdatePeerReview;

/**
 * A review contribution its author wants on ORCID, to a published report in a
 * flagged DFO series, by an author connected to ORCID
 *
 * @param  array<string, mixed>  $attributes
 */
function wantedReviewContribution(array $attributes = [], ?Author $author = null): ManuscriptReviewContributor
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
        'author_id' => ($author ?? Author::factory()->withOrcidConnection()->create())->id,
        'review_date' => '2025-03-14',
    ]);
    $contributor->forceFill(['orcid_sync' => OrcidSyncIntent::WANTED, ...$attributes])->save();

    return $contributor;
}

/**
 * Run the job like a queue worker would, without dispatching it
 */
function runReviewSync(ManuscriptReviewContributor $contributor): SyncReviewContributionWithOrcid
{
    $job = new SyncReviewContributionWithOrcid($contributor)->withFakeQueueInteractions();
    $job->handle(resolve(OrcidClientFactory::class));

    return $job;
}

/**
 * An ORCID API error response
 */
function orcidPeerReviewError(int $status, ?int $errorCode = null, string $message = 'error'): MockResponse
{
    return MockResponse::make(['response-code' => $status, 'developer-message' => $message, 'error-code' => $errorCode], $status);
}

function createdPeerReview(int $putCode = 4567): MockResponse
{
    return MockResponse::make('', 201, ['Location' => "https://api.orcid.org/v3.0/0000-0002-1825-0097/peer-review/{$putCode}"]);
}

beforeEach(function (): void {
    Queue::fake();
});

test('a wanted contribution is created on ORCID and its put-code stored', function (): void {
    $mockClient = Saloon::fake([CreatePeerReview::class => createdPeerReview()]);
    $contributor = wantedReviewContribution();
    $updatedAt = $contributor->updated_at;

    runReviewSync($contributor)->assertNotFailed();

    $contributor->refresh();
    expect($contributor->orcid_putcode)->toBe(4567)
        ->and($contributor->updated_at->equalTo($updatedAt))->toBeTrue()
        ->and($contributor->needsSyncWithOrcid())->toBeFalse();
    $mockClient->assertSent(CreatePeerReview::class);
    expect(Activity::query()->where('description', 'manuscript.review-contributor.orcid-created')->where('subject_id', $contributor->manuscript_record_id)->exists())->toBeTrue();
});

test('the completion date falls back to when the contributor was added', function (): void {
    $mockClient = Saloon::fake([CreatePeerReview::class => createdPeerReview()]);
    $contributor = wantedReviewContribution(['review_date' => null, 'created_at' => '2024-11-02 10:00:00']);

    runReviewSync($contributor)->assertNotFailed();

    $mockClient->assertSent(fn (CreatePeerReview $request): bool => $request->body()->get('review-completion-date')
        === ['year' => ['value' => '2024'], 'month' => ['value' => '11'], 'day' => ['value' => '02']]);
});

test('a synced contribution that is out of date is updated rather than recreated', function (): void {
    $mockClient = Saloon::fake([UpdatePeerReview::class => MockResponse::make(['put-code' => 4567])]);
    $contributor = wantedReviewContribution(['orcid_putcode' => 4567, 'orcid_updated_at' => now()->subDay()]);

    runReviewSync($contributor)->assertNotFailed();

    $mockClient->assertSent(fn (UpdatePeerReview $request): bool => $request->body()->get('put-code') === 4567);
    $mockClient->assertNotSent(CreatePeerReview::class);
    expect($contributor->refresh()->orcid_putcode)->toBe(4567)
        ->and($contributor->needsSyncWithOrcid())->toBeFalse();
});

test('a peer review the author deleted on ORCID is declined, not created again', function (): void {
    $mockClient = Saloon::fake([UpdatePeerReview::class => orcidPeerReviewError(404, 9016)]);
    $contributor = wantedReviewContribution(['orcid_putcode' => 4567, 'orcid_updated_at' => null]);

    runReviewSync($contributor)->assertNotFailed();

    $mockClient->assertNotSent(CreatePeerReview::class);
    expect($contributor->refresh()->orcid_sync)->toBe(OrcidSyncIntent::DECLINED)
        ->and($contributor->orcid_putcode)->toBeNull();
});

test('a declined contribution on ORCID is deleted there and its put-code cleared', function (MockResponse $deleteResponse): void {
    $mockClient = Saloon::fake([DeletePeerReview::class => $deleteResponse]);
    $contributor = wantedReviewContribution(['orcid_sync' => OrcidSyncIntent::DECLINED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()]);

    runReviewSync($contributor)->assertNotFailed();

    $mockClient->assertSent(fn (DeletePeerReview $request, $response): bool => str_ends_with($response->getPendingRequest()->getUrl(), '/peer-review/4567'));
    $contributor->refresh();
    expect($contributor->orcid_putcode)->toBeNull()
        ->and($contributor->orcid_updated_at)->toBeNull()
        ->and($contributor->orcid_sync)->toBe(OrcidSyncIntent::DECLINED);
    expect(Activity::query()->where('description', 'manuscript.review-contributor.orcid-deleted')->where('subject_id', $contributor->manuscript_record_id)->exists())->toBeTrue();
})->with([
    'deleted' => [MockResponse::make('', 204)],
    'already gone' => [orcidPeerReviewError(404, 9016)],
]);

test('a contribution on ORCID that is no longer eligible is deleted there', function (Closure $makeIneligible): void {
    $mockClient = Saloon::fake([DeletePeerReview::class => MockResponse::make('', 204)]);
    $contributor = wantedReviewContribution(['orcid_putcode' => 4567, 'orcid_updated_at' => now()]);
    $makeIneligible($contributor);

    runReviewSync($contributor->fresh())->assertNotFailed();

    $mockClient->assertSent(DeletePeerReview::class);
    expect(ManuscriptReviewContributor::withTrashed()->find($contributor->id)->orcid_putcode)->toBeNull();
})->with([
    'publication deleted' => [fn (ManuscriptReviewContributor $contributor) => $contributor->manuscriptRecord->publication->delete()],
    'manuscript deleted' => [fn (ManuscriptReviewContributor $contributor) => $contributor->manuscriptRecord->delete()],
]);

test('a removed contributor is deleted on ORCID, then removed for good', function (MockResponse $deleteResponse): void {
    $mockClient = Saloon::fake([DeletePeerReview::class => $deleteResponse]);
    $contributor = wantedReviewContribution(['orcid_putcode' => 4567, 'orcid_updated_at' => now()]);
    $contributor->delete();

    runReviewSync($contributor)->assertNotFailed();

    $mockClient->assertSent(DeletePeerReview::class);
    expect(ManuscriptReviewContributor::withTrashed()->find($contributor->id))->toBeNull();
})->with([
    'deleted' => [MockResponse::make('', 204)],
    'already gone' => [orcidPeerReviewError(404, 9016)],
]);

test('a contributor removed while ORCID creates its item keeps the put-code, so the item is deleted', function (): void {
    $contributor = wantedReviewContribution();
    $mockClient = Saloon::fake([
        CreatePeerReview::class => function () use ($contributor): MockResponse {
            ManuscriptReviewContributor::query()->find($contributor->id)->delete();

            return createdPeerReview();
        },
        DeletePeerReview::class => MockResponse::make('', 204),
    ]);

    runReviewSync($contributor)->assertNotFailed();
    expect(ManuscriptReviewContributor::withTrashed()->find($contributor->id)->orcid_putcode)->toBe(4567);

    runReviewSync(ManuscriptReviewContributor::withTrashed()->find($contributor->id))->assertNotFailed();

    $mockClient->assertSent(DeletePeerReview::class);
    expect(ManuscriptReviewContributor::withTrashed()->find($contributor->id))->toBeNull();
});

test('a removed contributor stays until its ORCID item can be deleted', function (): void {
    $mockClient = Saloon::fake([]);
    $contributor = wantedReviewContribution(
        ['orcid_putcode' => 4567],
        Author::factory()->withOrcidConnection('/read-limited')->create(),
    );
    $contributor->delete();

    runReviewSync($contributor)->assertNotFailed();

    $mockClient->assertNothingSent();
    expect(ManuscriptReviewContributor::withTrashed()->find($contributor->id)->orcid_putcode)->toBe(4567);
});

test('a peer review deleted on ORCID while the author re-added it keeps the new intent', function (): void {
    $contributor = wantedReviewContribution(['orcid_putcode' => 4567, 'orcid_updated_at' => null]);
    Saloon::fake([
        UpdatePeerReview::class => function () use ($contributor): MockResponse {
            $this->travel(1)->seconds();
            ManuscriptReviewContributor::query()->find($contributor->id)->forceFill(['orcid_sync' => OrcidSyncIntent::WANTED])->touch();

            return orcidPeerReviewError(404, 9016);
        },
    ]);

    runReviewSync($contributor)->assertNotFailed();

    $contributor->refresh();
    expect($contributor->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($contributor->orcid_putcode)->toBeNull()
        ->and($contributor->needsSyncWithOrcid())->toBeTrue();
});

test('a removal waits for an ORCID connection with the write scope', function (): void {
    $mockClient = Saloon::fake([]);
    $contributor = wantedReviewContribution(
        ['orcid_sync' => OrcidSyncIntent::DECLINED, 'orcid_putcode' => 4567],
        Author::factory()->withOrcidConnection('/read-limited')->create(),
    );

    runReviewSync($contributor)->assertNotFailed();

    $mockClient->assertNothingSent();
    expect($contributor->refresh()->orcid_putcode)->toBe(4567);
});

test('a removal refused for a missing scope disconnects the author and keeps the put-code', function (): void {
    Saloon::fake([DeletePeerReview::class => orcidPeerReviewError(403, 9038)]);
    $contributor = wantedReviewContribution(['orcid_sync' => OrcidSyncIntent::DECLINED, 'orcid_putcode' => 4567]);

    runReviewSync($contributor)->assertFailed();

    expect($contributor->author->refresh()->orcid_access_token)->toBeNull()
        ->and($contributor->refresh()->orcid_putcode)->toBe(4567);
});

test('a peer review already created by an earlier attempt is adopted', function (): void {
    $mockClient = Saloon::fake([
        CreatePeerReview::class => orcidPeerReviewError(409, 9021, '409 Conflict: You have already added this activity (matched by external identifiers), please see element with put-code 4567. If you are trying to edit the item, please use PUT instead of POST.'),
        UpdatePeerReview::class => MockResponse::make(['put-code' => 4567]),
    ]);
    $contributor = wantedReviewContribution();

    runReviewSync($contributor)->assertNotFailed();

    $mockClient->assertSent(fn (UpdatePeerReview $request): bool => $request->body()->get('put-code') === 4567);
    expect($contributor->refresh()->orcid_putcode)->toBe(4567)
        ->and($contributor->needsSyncWithOrcid())->toBeFalse();
});

test('a contribution changed while ORCID is being updated stays pending', function (): void {
    $contributor = wantedReviewContribution();
    Saloon::fake([
        CreatePeerReview::class => function () use ($contributor): MockResponse {
            ManuscriptReviewContributor::query()->find($contributor->id)->update(['review_date' => '2025-04-01']);

            return createdPeerReview();
        },
    ]);

    runReviewSync($contributor)->assertNotFailed();

    $contributor->refresh();
    expect($contributor->orcid_putcode)->toBe(4567) // kept, so the next sync does not create a duplicate
        ->and($contributor->orcid_updated_at)->toBeNull();
});

test('a contribution already in sync, or not wanted, is left alone', function (array $attributes): void {
    $mockClient = Saloon::fake([]);
    $contributor = wantedReviewContribution($attributes);

    runReviewSync($contributor)->assertNotFailed();

    $mockClient->assertNothingSent();
})->with([
    'in sync' => [['orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()]],
    'undecided' => [['orcid_sync' => null]],
    'declined' => [['orcid_sync' => OrcidSyncIntent::DECLINED]],
]);

test('contributions stay pending when ORCID cannot be written to', function (Closure $makeContributor): void {
    $mockClient = Saloon::fake([]);
    $contributor = $makeContributor();

    runReviewSync($contributor)->assertNotFailed();

    $mockClient->assertNothingSent();
    expect($contributor->refresh()->orcid_putcode)->toBeNull()
        ->and($contributor->needsSyncWithOrcid())->toBeTrue();
})->with([
    'author not connected' => [fn (): ManuscriptReviewContributor => wantedReviewContribution(author: Author::factory()->create(['orcid_verified' => false]))],
    'token without the write scope' => [fn (): ManuscriptReviewContributor => wantedReviewContribution(author: Author::factory()->withOrcidConnection('/read-limited')->create())],
    'expired token' => [fn (): ManuscriptReviewContributor => wantedReviewContribution(author: Author::factory()->withOrcidConnection()->create(['orcid_expires_at' => now()->subDay()]))],
    'DFO without ROR' => [function (): ManuscriptReviewContributor {
        Organization::getDefaultOrganization()->update(['ror_identifier' => null]);

        return wantedReviewContribution();
    }],
]);

test('a revoked token disconnects the author and fails the job', function (): void {
    Saloon::fake([CreatePeerReview::class => MockResponse::make(['error' => 'invalid_token', 'error_description' => 'Invalid access token'], 401)]);
    $contributor = wantedReviewContribution();

    runReviewSync($contributor)->assertFailed();

    expect($contributor->author->refresh()->orcid_access_token)->toBeNull()
        ->and($contributor->author->orcid_verified)->toBeFalse();
});

test('a token missing the write scope disconnects the author', function (): void {
    Saloon::fake([CreatePeerReview::class => orcidPeerReviewError(403, 9038)]);
    $contributor = wantedReviewContribution();

    runReviewSync($contributor)->assertFailed();

    expect($contributor->author->refresh()->orcid_access_token)->toBeNull();
});

test('"not the source" fails the job without disconnecting the author', function (): void {
    Saloon::fake([UpdatePeerReview::class => orcidPeerReviewError(403, 9010)]);
    $contributor = wantedReviewContribution(['orcid_putcode' => 4567, 'orcid_updated_at' => null]);

    runReviewSync($contributor)->assertFailed();

    expect($contributor->author->refresh()->orcid_access_token)->not->toBeNull();
});

test('a rejected payload fails the job without retrying', function (): void {
    Saloon::fake([CreatePeerReview::class => orcidPeerReviewError(400, 9045)]);

    runReviewSync(wantedReviewContribution())->assertFailed();
});

test('ORCID outages are retried', function (): void {
    Saloon::fake([CreatePeerReview::class => MockResponse::make('<html>Service Unavailable</html>', 503)]);
    $contributor = wantedReviewContribution();

    expect(fn (): SyncReviewContributionWithOrcid => runReviewSync($contributor))->toThrow(OrcidServerException::class);

    expect($contributor->author->refresh()->orcid_access_token)->not->toBeNull()
        ->and($contributor->refresh()->orcid_putcode)->toBeNull();
});

test('pending additions and removals are dispatched when an author reconnects', function (): void {
    $author = Author::factory()->withOrcidConnection()->create();
    $new = wantedReviewContribution(author: $author);
    $outOfDate = wantedReviewContribution(['orcid_putcode' => 1, 'orcid_updated_at' => null], $author);
    wantedReviewContribution(['orcid_putcode' => 2, 'orcid_updated_at' => now()->addMinute()], $author);
    wantedReviewContribution(['orcid_sync' => null], $author);
    wantedReviewContribution(['orcid_sync' => OrcidSyncIntent::DECLINED], $author);
    $toRemove = wantedReviewContribution(['orcid_sync' => OrcidSyncIntent::DECLINED, 'orcid_putcode' => 3, 'orcid_updated_at' => now()->addMinute()], $author);
    $removedFromManuscript = wantedReviewContribution(['orcid_putcode' => 4, 'orcid_updated_at' => now()->addMinute()], $author);
    $removedFromManuscript->delete();

    expect(SyncReviewContributionWithOrcid::dispatchPendingFor($author))->toBe(4);

    $dispatched = Queue::pushed(SyncReviewContributionWithOrcid::class)
        ->map(fn (SyncReviewContributionWithOrcid $job): int => $job->contributor->id)
        ->sort()->values()->all();

    expect($dispatched)->toBe(collect([$new->id, $outOfDate->id, $toRemove->id, $removedFromManuscript->id])->sort()->values()->all());
});
