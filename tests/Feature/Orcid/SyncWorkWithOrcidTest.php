<?php

use App\Enums\OrcidSyncIntent;
use App\Jobs\SyncWorkWithOrcid;
use App\Models\Author;
use App\Models\Journal;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use App\Orcid\OrcidClientFactory;
use Illuminate\Support\Facades\Queue;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Exceptions\OrcidServerException;
use VincentAuger\OrcidSdk\Requests\Works\CreateWork;
use VincentAuger\OrcidSdk\Requests\Works\DeleteWork;
use VincentAuger\OrcidSdk\Requests\Works\UpdateWork;

/**
 * A publication author who wants the publication on ORCID: a published report
 * with a DOI in a flagged DFO series, by an author connected to ORCID
 *
 * @param  array<string, mixed>  $attributes
 */
function wantedWork(array $attributes = [], ?Author $author = null): PublicationAuthor
{
    $publication = Publication::factory()->published()->create([
        'title' => 'Stock assessment of <i>Gadus morhua</i>',
        'doi' => '10.60825/abc-123',
        'published_on' => '2025-03-14',
        'journal_id' => Journal::factory()->dfoSeries()->create(['title' => 'Canadian Science Advisory Secretariat Research Document', 'issn' => '1919-5079', 'orcid_sync_enabled' => true])->id,
    ]);

    $publicationAuthor = PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => ($author ?? Author::factory()->withOrcidConnection()->create())->id,
    ]);
    $publicationAuthor->forceFill(['orcid_sync' => OrcidSyncIntent::WANTED, ...$attributes])->save();

    return $publicationAuthor;
}

/**
 * Run the job like a queue worker would, without dispatching it
 */
function runWorkSync(PublicationAuthor $publicationAuthor): SyncWorkWithOrcid
{
    $job = new SyncWorkWithOrcid($publicationAuthor)->withFakeQueueInteractions();
    $job->handle(resolve(OrcidClientFactory::class));

    return $job;
}

/**
 * An ORCID API error response
 */
function orcidWorkError(int $status, ?int $errorCode = null, string $message = 'error'): MockResponse
{
    return MockResponse::make(['response-code' => $status, 'developer-message' => $message, 'error-code' => $errorCode], $status);
}

function createdWork(int $putCode = 4567): MockResponse
{
    return MockResponse::make('', 201, ['Location' => "https://api.orcid.org/v3.0/0000-0002-1825-0097/work/{$putCode}"]);
}

beforeEach(function (): void {
    Queue::fake();
});

test('a wanted work is created on ORCID and its put-code stored', function (): void {
    $mockClient = Saloon::fake([CreateWork::class => createdWork()]);
    $publicationAuthor = wantedWork();
    $publication = $publicationAuthor->publication;
    $coAuthor = PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => Author::factory()->create(['first_name' => 'Josiah', 'last_name' => 'Carberry', 'orcid' => 'https://orcid.org/0000-0002-1825-0097'])->id,
    ]);
    $groupAuthor = PublicationAuthor::factory()->groupAuthor()->create([
        'publication_id' => $publication->id,
        'organization_id' => Organization::factory()->create(['name_en' => 'Fisheries and Oceans Canada'])->id,
        'author_id' => Author::factory()->create(['orcid' => null])->id,
    ]);
    $author = $publicationAuthor->author;
    $updatedAt = $publicationAuthor->updated_at;

    runWorkSync($publicationAuthor)->assertNotFailed();

    $publicationAuthor->refresh();
    expect($publicationAuthor->orcid_putcode)->toBe(4567)
        ->and($publicationAuthor->updated_at->equalTo($updatedAt))->toBeTrue()
        ->and($publicationAuthor->needsSyncWithOrcid())->toBeFalse();
    $mockClient->assertSent(fn (CreateWork $request): bool => $request->body()->all() === [
        'title' => ['title' => ['value' => 'Stock assessment of Gadus morhua']],
        'journal-title' => ['value' => 'Canadian Science Advisory Secretariat Research Document'],
        'type' => 'report',
        'publication-date' => ['year' => ['value' => '2025'], 'month' => ['value' => '03'], 'day' => ['value' => '14']],
        'external-ids' => ['external-id' => [
            [
                'external-id-type' => 'doi',
                'external-id-value' => '10.60825/abc-123',
                'external-id-url' => ['value' => 'https://doi.org/10.60825/abc-123'],
                'external-id-relationship' => 'self',
            ],
            [
                'external-id-type' => 'issn',
                'external-id-value' => '1919-5079',
                'external-id-url' => ['value' => 'https://portal.issn.org/resource/ISSN/1919-5079'],
                'external-id-relationship' => 'part-of',
            ],
        ]],
        'url' => ['value' => 'https://doi.org/10.60825/abc-123'],
        'contributors' => ['contributor' => [
            [
                'contributor-orcid' => ['path' => '0000-0002-1825-0097'],
                'credit-name' => ['value' => $author->full_name],
            ],
            [
                'contributor-orcid' => ['path' => '0000-0002-1825-0097'],
                'credit-name' => ['value' => 'Josiah Carberry'],
            ],
            [
                'credit-name' => ['value' => 'Fisheries and Oceans Canada'],
            ],
        ]],
    ]);
    expect(Activity::query()->where('description', 'publication.author.orcid-created')->where('subject_type', Publication::class)->where('subject_id', $publication->id)->exists())->toBeTrue();
});

test('a series without a valid ISSN is synced without it', function (?string $issn): void {
    $mockClient = Saloon::fake([CreateWork::class => createdWork()]);
    $publicationAuthor = wantedWork();
    $publicationAuthor->publication->journal->update(['issn' => $issn]);

    runWorkSync($publicationAuthor->fresh())->assertNotFailed();

    $mockClient->assertSent(fn (CreateWork $request): bool => array_column($request->body()->get('external-ids')['external-id'], 'external-id-type') === ['doi']);
})->with([
    'no ISSN' => [null],
    'bad check digit' => ['1488-XXXE'],
]);

test('a co-author\'s malformed ORCID iD is left out rather than holding back the work', function (): void {
    $mockClient = Saloon::fake([CreateWork::class => createdWork()]);
    $publicationAuthor = wantedWork();
    PublicationAuthor::factory()->create([
        'publication_id' => $publicationAuthor->publication_id,
        'author_id' => Author::factory()->create(['first_name' => 'Josiah', 'last_name' => 'Carberry', 'orcid' => '0000-0002-1825-0098'])->id,
    ]);

    runWorkSync($publicationAuthor)->assertNotFailed();

    $mockClient->assertSent(fn (CreateWork $request): bool => $request->body()->get('contributors')['contributor'][1] === ['credit-name' => ['value' => 'Josiah Carberry']]);
    expect($publicationAuthor->refresh()->orcid_putcode)->toBe(4567);
});

test('a work with data ORCID would reject stays pending without calling ORCID', function (): void {
    $mockClient = Saloon::fake([]);
    $publicationAuthor = wantedWork();
    $publicationAuthor->publication->update(['doi' => 'https://example.org/10.60825/abc-123']);

    runWorkSync($publicationAuthor->fresh())->assertNotFailed();

    $mockClient->assertNothingSent();
    expect($publicationAuthor->author->refresh()->orcid_access_token)->not->toBeNull()
        ->and($publicationAuthor->refresh()->orcid_putcode)->toBeNull()
        ->and($publicationAuthor->needsSyncWithOrcid())->toBeTrue();
});

test('syncing is logged on the publication, not as a change to the author', function (): void {
    Saloon::fake([CreateWork::class => createdWork()]);
    $publicationAuthor = wantedWork();

    runWorkSync($publicationAuthor)->assertNotFailed();

    expect(Activity::query()->whereMorphedTo('subject', $publicationAuthor)->where('event', 'updated')->exists())->toBeFalse();
});

test('a synced work that is out of date is updated rather than recreated', function (): void {
    $mockClient = Saloon::fake([UpdateWork::class => MockResponse::make(['put-code' => 4567, 'title' => ['title' => ['value' => 'Stock assessment']], 'type' => 'report'])]);
    $publicationAuthor = wantedWork(['orcid_putcode' => 4567, 'orcid_updated_at' => now()->subDay()]);

    runWorkSync($publicationAuthor)->assertNotFailed();

    $mockClient->assertSent(fn (UpdateWork $request): bool => $request->body()->get('put-code') === 4567);
    $mockClient->assertNotSent(CreateWork::class);
    expect($publicationAuthor->refresh()->orcid_putcode)->toBe(4567)
        ->and($publicationAuthor->needsSyncWithOrcid())->toBeFalse();
});

test('a work already created by an earlier attempt is adopted', function (): void {
    $mockClient = Saloon::fake([
        CreateWork::class => orcidWorkError(409, 9021, '409 Conflict: You have already added this activity (matched by external identifiers), please see element with put-code 4567. If you are trying to edit the item, please use PUT instead of POST.'),
        UpdateWork::class => MockResponse::make(['put-code' => 4567, 'title' => ['title' => ['value' => 'Stock assessment']], 'type' => 'report']),
    ]);
    $publicationAuthor = wantedWork();

    runWorkSync($publicationAuthor)->assertNotFailed();

    $mockClient->assertSent(fn (UpdateWork $request): bool => $request->body()->get('put-code') === 4567);
    expect($publicationAuthor->refresh()->orcid_putcode)->toBe(4567)
        ->and($publicationAuthor->needsSyncWithOrcid())->toBeFalse();
});

test('a work the author deleted on ORCID is declined, not created again', function (): void {
    $mockClient = Saloon::fake([UpdateWork::class => orcidWorkError(404, 9016)]);
    $publicationAuthor = wantedWork(['orcid_putcode' => 4567, 'orcid_updated_at' => null]);

    runWorkSync($publicationAuthor)->assertNotFailed();

    $mockClient->assertNotSent(CreateWork::class);
    expect($publicationAuthor->refresh()->orcid_sync)->toBe(OrcidSyncIntent::DECLINED)
        ->and($publicationAuthor->orcid_putcode)->toBeNull();
    expect(Activity::query()->where('description', 'publication.author.orcid-deleted-by-author')->where('subject_id', $publicationAuthor->publication_id)->exists())->toBeTrue();
});

test('a work deleted on ORCID while the author re-added it keeps the new intent', function (): void {
    $publicationAuthor = wantedWork(['orcid_putcode' => 4567, 'orcid_updated_at' => null]);
    Saloon::fake([
        UpdateWork::class => function () use ($publicationAuthor): MockResponse {
            $this->travel(1)->seconds();
            $current = PublicationAuthor::query()->find($publicationAuthor->id);
            $author = User::factory()->create();
            $current->recordOrcidIntent(OrcidSyncIntent::DECLINED, $author);
            $current->recordOrcidIntent(OrcidSyncIntent::WANTED, $author);

            return orcidWorkError(404, 9016);
        },
    ]);

    runWorkSync($publicationAuthor)->assertNotFailed();

    $publicationAuthor->refresh();
    expect($publicationAuthor->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($publicationAuthor->orcid_putcode)->toBeNull()
        ->and($publicationAuthor->needsSyncWithOrcid())->toBeTrue();
});

test('a declined work on ORCID is deleted there and its put-code cleared', function (MockResponse $deleteResponse): void {
    $mockClient = Saloon::fake([DeleteWork::class => $deleteResponse]);
    $publicationAuthor = wantedWork(['orcid_sync' => OrcidSyncIntent::DECLINED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()]);

    runWorkSync($publicationAuthor)->assertNotFailed();

    $mockClient->assertSent(fn (DeleteWork $request, $response): bool => str_ends_with($response->getPendingRequest()->getUrl(), '/work/4567'));
    $publicationAuthor->refresh();
    expect($publicationAuthor->orcid_putcode)->toBeNull()
        ->and($publicationAuthor->orcid_updated_at)->toBeNull()
        ->and($publicationAuthor->orcid_sync)->toBe(OrcidSyncIntent::DECLINED);
    expect(Activity::query()->where('description', 'publication.author.orcid-deleted')->where('subject_id', $publicationAuthor->publication_id)->exists())->toBeTrue();
})->with([
    'deleted' => [MockResponse::make('', 204)],
    'already gone' => [orcidWorkError(404, 9016)],
]);

test('a work on ORCID whose publication is deleted is deleted there, keeping the row and intent', function (): void {
    $mockClient = Saloon::fake([DeleteWork::class => MockResponse::make('', 204)]);
    $publicationAuthor = wantedWork(['orcid_putcode' => 4567, 'orcid_updated_at' => now()]);
    $publication = $publicationAuthor->publication;
    $publication->publicationAuthors()->delete();
    $publication->delete();

    runWorkSync(PublicationAuthor::withTrashed()->find($publicationAuthor->id))->assertNotFailed();

    $mockClient->assertSent(DeleteWork::class);
    $kept = PublicationAuthor::withTrashed()->find($publicationAuthor->id);
    expect($kept)->not->toBeNull()
        ->and($kept->orcid_putcode)->toBeNull()
        ->and($kept->orcid_sync)->toBe(OrcidSyncIntent::WANTED);
    expect(Activity::query()->where('description', 'publication.author.orcid-deleted')->where('subject_id', $publication->id)->exists())->toBeTrue();
});

test('a work on ORCID that is no longer eligible is deleted there', function (Closure $makeIneligible): void {
    $mockClient = Saloon::fake([DeleteWork::class => MockResponse::make('', 204)]);
    $publicationAuthor = wantedWork(['orcid_putcode' => 4567, 'orcid_updated_at' => now()]);
    $makeIneligible($publicationAuthor->publication);

    runWorkSync($publicationAuthor->fresh())->assertNotFailed();

    $mockClient->assertSent(DeleteWork::class);
    expect($publicationAuthor->fresh()->orcid_putcode)->toBeNull();
})->with([
    'journal no longer synced' => [fn (Publication $publication) => $publication->journal->update(['orcid_sync_enabled' => false])],
    'DOI removed' => [fn (Publication $publication) => $publication->update(['doi' => null])],
]);

test('a work the author deleted on ORCID before it stopped being eligible is declined', function (Closure $makeIneligible): void {
    Saloon::fake([DeleteWork::class => orcidWorkError(404, 9016)]);
    $publicationAuthor = wantedWork(['orcid_putcode' => 4567, 'orcid_updated_at' => now()]);
    $makeIneligible($publicationAuthor);

    runWorkSync(PublicationAuthor::withTrashed()->find($publicationAuthor->id))->assertNotFailed();

    $publicationAuthor = PublicationAuthor::withTrashed()->find($publicationAuthor->id);
    expect($publicationAuthor)->not->toBeNull()
        ->and($publicationAuthor->orcid_putcode)->toBeNull()
        ->and($publicationAuthor->orcid_sync)->toBe(OrcidSyncIntent::DECLINED);
    expect(Activity::query()->where('description', 'publication.author.orcid-deleted-by-author')->where('subject_id', $publicationAuthor->publication_id)->exists())->toBeTrue();
})->with([
    'journal no longer synced' => [fn (PublicationAuthor $publicationAuthor) => $publicationAuthor->publication->journal->update(['orcid_sync_enabled' => false])],
    'publication deleted' => [function (PublicationAuthor $publicationAuthor): void {
        $publicationAuthor->delete();
        $publicationAuthor->publication->delete();
    }],
]);

test('a removed author is deleted on ORCID, then removed for good', function (MockResponse $deleteResponse): void {
    $mockClient = Saloon::fake([DeleteWork::class => $deleteResponse]);
    $publicationAuthor = wantedWork(['orcid_putcode' => 4567, 'orcid_updated_at' => now()]);
    $publicationAuthor->delete();

    runWorkSync($publicationAuthor)->assertNotFailed();

    $mockClient->assertSent(DeleteWork::class);
    expect(PublicationAuthor::withTrashed()->find($publicationAuthor->id))->toBeNull();
})->with([
    'deleted' => [MockResponse::make('', 204)],
    'already gone' => [orcidWorkError(404, 9016)],
]);

test('a removed author never synced is removed for good without calling ORCID', function (): void {
    $mockClient = Saloon::fake([]);
    $publicationAuthor = wantedWork();
    $publicationAuthor->delete();

    runWorkSync($publicationAuthor)->assertNotFailed();

    $mockClient->assertNothingSent();
    expect(PublicationAuthor::withTrashed()->find($publicationAuthor->id))->toBeNull();
});

test('an author removed while ORCID creates the work keeps the put-code, so the work is deleted', function (): void {
    $publicationAuthor = wantedWork();
    $mockClient = Saloon::fake([
        CreateWork::class => function () use ($publicationAuthor): MockResponse {
            PublicationAuthor::query()->find($publicationAuthor->id)->delete();

            return createdWork();
        },
        DeleteWork::class => MockResponse::make('', 204),
    ]);

    runWorkSync($publicationAuthor)->assertNotFailed();
    expect(PublicationAuthor::withTrashed()->find($publicationAuthor->id)->orcid_putcode)->toBe(4567);

    runWorkSync(PublicationAuthor::withTrashed()->find($publicationAuthor->id))->assertNotFailed();

    $mockClient->assertSent(DeleteWork::class);
    expect(PublicationAuthor::withTrashed()->find($publicationAuthor->id))->toBeNull();
});

test('a removed author stays until the work can be deleted on ORCID', function (): void {
    $mockClient = Saloon::fake([]);
    $publicationAuthor = wantedWork(
        ['orcid_putcode' => 4567],
        Author::factory()->withOrcidConnection('/read-limited')->create(),
    );
    $publicationAuthor->delete();

    runWorkSync($publicationAuthor)->assertNotFailed();

    $mockClient->assertNothingSent();
    expect(PublicationAuthor::withTrashed()->find($publicationAuthor->id)->orcid_putcode)->toBe(4567);
});

test('a publication changed while ORCID is being updated stays pending', function (): void {
    $publicationAuthor = wantedWork();
    Saloon::fake([
        CreateWork::class => function () use ($publicationAuthor): MockResponse {
            Publication::query()->find($publicationAuthor->publication_id)->update(['title' => 'A new title']);

            return createdWork();
        },
    ]);

    runWorkSync($publicationAuthor)->assertNotFailed();

    $publicationAuthor->refresh();
    expect($publicationAuthor->orcid_putcode)->toBe(4567) // kept, so the next sync does not create a duplicate
        ->and($publicationAuthor->orcid_updated_at)->toBeNull();
});

test('a work already in sync, or not wanted, is left alone', function (array $attributes): void {
    $mockClient = Saloon::fake([]);
    $publicationAuthor = wantedWork($attributes);

    runWorkSync($publicationAuthor)->assertNotFailed();

    $mockClient->assertNothingSent();
})->with([
    // built when the test runs, not when the dataset is loaded
    'in sync' => [fn (): array => ['orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()]],
    'undecided' => [['orcid_sync' => null]],
    'declined' => [['orcid_sync' => OrcidSyncIntent::DECLINED]],
]);

test('works stay pending when ORCID cannot be written to', function (Closure $makeAuthor): void {
    $mockClient = Saloon::fake([]);
    $publicationAuthor = wantedWork(author: $makeAuthor());

    runWorkSync($publicationAuthor)->assertNotFailed();

    $mockClient->assertNothingSent();
    expect($publicationAuthor->refresh()->orcid_putcode)->toBeNull()
        ->and($publicationAuthor->needsSyncWithOrcid())->toBeTrue();
})->with([
    'author not connected' => [fn (): Author => Author::factory()->create(['orcid_verified' => false])],
    'token without the write scope' => [fn (): Author => Author::factory()->withOrcidConnection('/read-limited')->create()],
    'expired token' => [fn (): Author => Author::factory()->withOrcidConnection()->create(['orcid_expires_at' => now()->subDay()])],
]);

test('a revoked token disconnects the author and fails the job', function (): void {
    Saloon::fake([CreateWork::class => MockResponse::make(['error' => 'invalid_token', 'error_description' => 'Invalid access token'], 401)]);
    $publicationAuthor = wantedWork();

    runWorkSync($publicationAuthor)->assertFailed();

    expect($publicationAuthor->author->refresh()->orcid_access_token)->toBeNull()
        ->and($publicationAuthor->author->orcid_verified)->toBeFalse();
});

test('"not the source" fails the job without disconnecting the author', function (): void {
    Saloon::fake([UpdateWork::class => orcidWorkError(403, 9010)]);
    $publicationAuthor = wantedWork(['orcid_putcode' => 4567, 'orcid_updated_at' => null]);

    runWorkSync($publicationAuthor)->assertFailed();

    expect($publicationAuthor->author->refresh()->orcid_access_token)->not->toBeNull();
});

test('a rejected payload fails the job without retrying', function (): void {
    Saloon::fake([CreateWork::class => orcidWorkError(400, 9045)]);

    runWorkSync(wantedWork())->assertFailed();
});

test('ORCID outages are retried', function (): void {
    Saloon::fake([CreateWork::class => MockResponse::make('<html>Service Unavailable</html>', 503)]);
    $publicationAuthor = wantedWork();

    expect(fn (): SyncWorkWithOrcid => runWorkSync($publicationAuthor))->toThrow(OrcidServerException::class);

    expect($publicationAuthor->author->refresh()->orcid_access_token)->not->toBeNull()
        ->and($publicationAuthor->refresh()->orcid_putcode)->toBeNull();
});

test('pending additions and removals are dispatched when an author reconnects', function (): void {
    $author = Author::factory()->withOrcidConnection()->create();
    $new = wantedWork(author: $author);
    $outOfDate = wantedWork(['orcid_putcode' => 1, 'orcid_updated_at' => null], $author);
    wantedWork(['orcid_putcode' => 2, 'orcid_updated_at' => now()->addMinute()], $author);
    wantedWork(['orcid_sync' => null], $author);
    wantedWork(['orcid_sync' => OrcidSyncIntent::DECLINED], $author);
    $toRemove = wantedWork(['orcid_sync' => OrcidSyncIntent::DECLINED, 'orcid_putcode' => 3, 'orcid_updated_at' => now()->addMinute()], $author);
    $removedFromPublication = wantedWork(['orcid_putcode' => 4, 'orcid_updated_at' => now()->addMinute()], $author);
    $removedFromPublication->delete();
    $publicationDeleted = wantedWork(['orcid_putcode' => 5, 'orcid_updated_at' => now()->addMinute()], $author);
    $publicationDeleted->delete();
    $publicationDeleted->publication->delete();
    $publicationDeletedOffOrcid = wantedWork(author: $author);
    $publicationDeletedOffOrcid->delete();
    $publicationDeletedOffOrcid->publication->delete();

    expect(SyncWorkWithOrcid::dispatchPendingFor($author))->toBe(5);

    $dispatched = Queue::pushed(SyncWorkWithOrcid::class)
        ->map(fn (SyncWorkWithOrcid $job): int => $job->publicationAuthor->id)
        ->sort()->values()->all();

    expect($dispatched)->toBe(collect([$new->id, $outOfDate->id, $toRemove->id, $removedFromPublication->id, $publicationDeleted->id])->sort()->values()->all());
});
