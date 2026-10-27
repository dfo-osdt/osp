<?php

use App\Jobs\SyncAuthorEmploymentWithOrcid;
use App\Models\Author;
use App\Models\AuthorEmployment;
use App\Models\Organization;
use App\Orcid\OrcidClientFactory;
use Illuminate\Support\Facades\Queue;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use VincentAuger\OrcidSdk\Exceptions\OrcidServerException;
use VincentAuger\OrcidSdk\Requests\Affiliations\CreateAffiliation;
use VincentAuger\OrcidSdk\Requests\Affiliations\DeleteAffiliation;
use VincentAuger\OrcidSdk\Requests\Affiliations\UpdateAffiliation;

/**
 * An employment of an author connected to ORCID
 *
 * @param  array<string, mixed>  $attributes
 */
function orcidEmployment(array $attributes = [], ?Author $author = null): AuthorEmployment
{
    return AuthorEmployment::factory()->create([
        'author_id' => ($author ?? Author::factory()->withOrcidConnection()->create())->id,
        'organization_id' => Organization::getDefaultOrganization()->id,
        'start_date' => '2021-01-05',
        ...$attributes,
    ]);
}

/**
 * Run the job like a queue worker would, without dispatching it
 */
function runOrcidSync(AuthorEmployment $employment): SyncAuthorEmploymentWithOrcid
{
    $job = new SyncAuthorEmploymentWithOrcid($employment)->withFakeQueueInteractions();
    $job->handle(resolve(OrcidClientFactory::class));

    return $job;
}

/**
 * An ORCID API error response
 */
function orcidError(int $status, ?int $errorCode = null): MockResponse
{
    return MockResponse::make(['response-code' => $status, 'developer-message' => "{$status} error", 'error-code' => $errorCode], $status);
}

beforeEach(function (): void {
    Queue::fake(); // employments created by factories must not dispatch real syncs
});

test('a new employment is created on ORCID and its put-code stored', function (): void {
    $mockClient = Saloon::fake([
        CreateAffiliation::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/employment/12345']),
    ]);
    $employment = orcidEmployment(['role_title' => 'Biologist', 'department_name' => '']);
    $updatedAt = $employment->updated_at;

    runOrcidSync($employment)->assertNotFailed();

    $employment->refresh();
    expect($employment->orcid_putcode)->toBe(12345)
        ->and($employment->orcid_updated_at)->not->toBeNull()
        ->and($employment->updated_at->equalTo($updatedAt))->toBeTrue()
        ->and($employment->needsSyncWithOrcid())->toBeFalse();

    $mockClient->assertSent(function (CreateAffiliation $request, $response): bool {
        $body = $request->body()->all();

        return str_ends_with($response->getPendingRequest()->getUrl(), '/v3.0/0000-0002-1825-0097/employment')
            && $body['role-title'] === 'Biologist'
            && ! array_key_exists('department-name', $body) // blank: left out, ORCID rejects empty strings
            && $body['start-date'] === ['year' => ['value' => '2021'], 'month' => ['value' => '01'], 'day' => ['value' => '05']]
            && $body['organization']['address'] === ['city' => 'Ottawa', 'country' => 'CA']
            && $body['organization']['disambiguated-organization']['disambiguated-organization-identifier'] === 'https://ror.org/02qa1x782';
    });
});

test('a synced employment is updated on ORCID', function (): void {
    $mockClient = Saloon::fake([UpdateAffiliation::class => MockResponse::make(['put-code' => 12345], 200)]);
    $employment = orcidEmployment(['orcid_putcode' => 12345, 'orcid_updated_at' => null]);

    runOrcidSync($employment)->assertNotFailed();

    $mockClient->assertSent(fn (UpdateAffiliation $request): bool => $request->body()->get('put-code') === 12345);
    expect($employment->refresh()->needsSyncWithOrcid())->toBeFalse();
});

test('an employment the author deleted on ORCID is created again', function (): void {
    Saloon::fake([
        UpdateAffiliation::class => orcidError(404, 9016),
        CreateAffiliation::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/employment/999']),
    ]);
    $employment = orcidEmployment(['orcid_putcode' => 12345, 'orcid_updated_at' => null]);

    runOrcidSync($employment)->assertNotFailed();

    expect($employment->refresh()->orcid_putcode)->toBe(999);
});

test('a deleted employment is removed from ORCID, then from OSP', function (): void {
    $mockClient = Saloon::fake([DeleteAffiliation::class => MockResponse::make('', 204)]);
    $employment = orcidEmployment(['orcid_putcode' => 12345, 'orcid_updated_at' => now()]);
    $employment->delete();

    runOrcidSync($employment)->assertNotFailed();

    $mockClient->assertSent(DeleteAffiliation::class);
    expect(AuthorEmployment::withTrashed()->find($employment->id))->toBeNull();
});

test('a deleted employment that was never synced is removed without calling ORCID', function (): void {
    $mockClient = Saloon::fake([]);
    $employment = orcidEmployment(author: Author::factory()->create(['orcid_verified' => false]));
    $employment->delete();

    runOrcidSync($employment)->assertNotFailed();

    $mockClient->assertNothingSent();
    expect(AuthorEmployment::withTrashed()->find($employment->id))->toBeNull();
});

test('an employment already in sync is left alone', function (): void {
    $mockClient = Saloon::fake([]);
    $employment = orcidEmployment(['orcid_putcode' => 12345]);
    $employment->forceFill(['orcid_updated_at' => $employment->updated_at->addSecond()])->saveQuietly();

    runOrcidSync($employment)->assertNotFailed();

    $mockClient->assertNothingSent();
});

test('employments stay pending when ORCID cannot be written to', function (Closure $makeEmployment): void {
    $mockClient = Saloon::fake([]);
    $employment = $makeEmployment();

    runOrcidSync($employment)->assertNotFailed();

    $mockClient->assertNothingSent();
    expect($employment->refresh()->orcid_putcode)->toBeNull()
        ->and($employment->needsSyncWithOrcid())->toBeTrue();
})->with([
    'author not connected' => [fn (): AuthorEmployment => orcidEmployment(author: Author::factory()->create(['orcid_verified' => false]))],
    'token without the write scope' => [fn (): AuthorEmployment => orcidEmployment(author: Author::factory()->withOrcidConnection('/read-limited')->create())],
    'expired token' => [fn (): AuthorEmployment => orcidEmployment(author: Author::factory()->withOrcidConnection()->create(['orcid_expires_at' => now()->subDay()]))],
    'invalid ORCID iD' => [fn (): AuthorEmployment => orcidEmployment(author: Author::factory()->withOrcidConnection()->create(['orcid' => 'https://orcid.org/0000-0002-1825-0098']))],
    'end date before the start date' => [fn (): AuthorEmployment => orcidEmployment(['start_date' => '2021-01-05', 'end_date' => '2020-06-01'])],
    'year ORCID cannot store' => [fn (): AuthorEmployment => orcidEmployment(['start_date' => '0202-01-05'])],
    'organization other than DFO' => [fn (): AuthorEmployment => orcidEmployment(['organization_id' => Organization::factory()->create(['country_code' => 'CA', 'ror_identifier' => 'https://ror.org/03c4mmv16'])->id])],
    'DFO without ROR' => [function (): AuthorEmployment {
        Organization::getDefaultOrganization()->update(['ror_identifier' => null]);

        return orcidEmployment();
    }],
    'DFO without country' => [function (): AuthorEmployment {
        Organization::getDefaultOrganization()->update(['country_code' => null]);

        return orcidEmployment();
    }],
]);

test('a revoked token disconnects the author and fails the job', function (): void {
    Saloon::fake([CreateAffiliation::class => MockResponse::make(['error' => 'invalid_token', 'error_description' => 'Invalid access token'], 401)]);
    $employment = orcidEmployment();

    runOrcidSync($employment)->assertFailed();

    expect($employment->author->refresh()->orcid_access_token)->toBeNull()
        ->and($employment->author->orcid_verified)->toBeFalse();
});

test('a token missing the write scope disconnects the author', function (): void {
    Saloon::fake([CreateAffiliation::class => orcidError(403, 9038)]);
    $employment = orcidEmployment();

    runOrcidSync($employment)->assertFailed();

    expect($employment->author->refresh()->orcid_access_token)->toBeNull();
});

test('"not the source" fails the job without disconnecting the author', function (): void {
    Saloon::fake([UpdateAffiliation::class => orcidError(403, 9010)]);
    $employment = orcidEmployment(['orcid_putcode' => 12345, 'orcid_updated_at' => null]);

    runOrcidSync($employment)->assertFailed();

    expect($employment->author->refresh()->orcid_access_token)->not->toBeNull();
});

test('a rejected payload fails the job without retrying', function (): void {
    Saloon::fake([CreateAffiliation::class => orcidError(400, 9045)]);

    runOrcidSync(orcidEmployment())->assertFailed();
});

test('ORCID outages are retried', function (): void {
    Saloon::fake([CreateAffiliation::class => MockResponse::make('<html>Service Unavailable</html>', 503)]);
    $employment = orcidEmployment();

    expect(fn (): SyncAuthorEmploymentWithOrcid => runOrcidSync($employment))->toThrow(OrcidServerException::class);

    expect($employment->author->refresh()->orcid_access_token)->not->toBeNull()
        ->and($employment->refresh()->orcid_putcode)->toBeNull();
});

test('pending employments are dispatched when an author reconnects', function (): void {
    $author = Author::factory()->withOrcidConnection()->create();
    $new = orcidEmployment(author: $author);
    $changed = orcidEmployment(['orcid_putcode' => 1, 'orcid_updated_at' => null], $author);
    $inSync = orcidEmployment(['orcid_putcode' => 2], $author);
    $inSync->forceFill(['orcid_updated_at' => $inSync->updated_at->addSecond()])->saveQuietly();
    $deletedSynced = orcidEmployment(['orcid_putcode' => 3, 'orcid_updated_at' => now()], $author);
    $deletedSynced->delete();
    $deletedNeverSynced = orcidEmployment(author: $author);
    $deletedNeverSynced->delete();

    expect(SyncAuthorEmploymentWithOrcid::dispatchPendingFor($author))->toBe(3);

    $dispatched = Queue::pushed(SyncAuthorEmploymentWithOrcid::class)
        ->map(fn (SyncAuthorEmploymentWithOrcid $job): int => $job->authorEmployment->id)
        ->sort()->values()->all();

    expect($dispatched)->toBe(collect([$new->id, $changed->id, $deletedSynced->id])->sort()->values()->all());
});
