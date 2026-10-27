<?php

use App\Jobs\SyncAuthorEmploymentWithOrcid;
use App\Jobs\SyncAuthorFromOrcid;
use App\Models\Author;
use App\Models\AuthorEmployment;
use App\Models\Organization;
use App\Models\User;
use App\Orcid\EmploymentReconciler;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Exceptions\OrcidServerException;
use VincentAuger\OrcidSdk\Requests\Affiliations\ListAffiliations;

const OSP_CLIENT_ID = 'APP-0000000000000001';

/**
 * An employment summary as listed by ORCID, created by OSP a while ago
 *
 * @param  array<string, mixed>  $attributes
 * @return array<string, mixed>
 */
function orcidEmploymentSummary(int $putCode, array $attributes = []): array
{
    return [
        'put-code' => $putCode,
        'created-date' => ['value' => now()->subDay()->getTimestampMs()],
        'source' => ['source-client-id' => ['path' => OSP_CLIENT_ID], 'source-name' => ['value' => 'OSP']],
        'department-name' => 'Science',
        'role-title' => 'Biologist',
        'start-date' => ['year' => ['value' => '2021'], 'month' => ['value' => '01'], 'day' => ['value' => '05']],
        'end-date' => null,
        'organization' => [
            'name' => 'Fisheries and Oceans Canada',
            'address' => ['city' => 'Ottawa', 'country' => 'CA'],
            'disambiguated-organization' => ['disambiguated-organization-identifier' => 'https://ror.org/02qa1x782', 'disambiguation-source' => 'ROR'],
        ],
        ...$attributes,
    ];
}

/**
 * @param  array<array<string, mixed>>  $summaries
 */
function orcidEmploymentList(array $summaries): MockResponse
{
    return MockResponse::make(['affiliation-group' => array_map(
        fn (array $summary): array => ['summaries' => [['employment-summary' => $summary]]],
        $summaries,
    )]);
}

/**
 * An employment OSP synced with ORCID a while ago
 */
function syncedEmployment(Author $author, int $putCode): AuthorEmployment
{
    $employment = AuthorEmployment::factory()->create(['author_id' => $author->id, 'organization_id' => Organization::getDefaultOrganization()->id]);
    $employment->forceFill(['orcid_putcode' => $putCode, 'updated_at' => now()->subHour(), 'orcid_updated_at' => now()->subHour()])->saveQuietly();

    return $employment;
}

/**
 * A user whose author is connected to ORCID
 */
function connectedUser(): User
{
    $user = User::factory()->create();
    $orcidAttributes = array_filter(Author::factory()->withOrcidConnection()->raw(), fn (string $key): bool => str_starts_with($key, 'orcid'), ARRAY_FILTER_USE_KEY);
    $user->author->forceFill($orcidAttributes)->save();

    return $user;
}

beforeEach(function (): void {
    Queue::fake();
    config(['osp.orcid.client_id' => OSP_CLIENT_ID]);
});

test('employments OSP created on ORCID but no longer has are imported', function (): void {
    Saloon::fake([ListAffiliations::class => orcidEmploymentList([orcidEmploymentSummary(12345)])]);
    $author = Author::factory()->withOrcidConnection()->create();

    expect(resolve(EmploymentReconciler::class)->reconcile($author))->toBe(['imported' => 1, 'deleted' => 0]);

    $employment = $author->employments()->sole();
    expect($employment->orcid_putcode)->toBe(12345)
        ->and($employment->organization->is(Organization::getDefaultOrganization()))->toBeTrue()
        ->and($employment->role_title)->toBe('Biologist')
        ->and($employment->department_name)->toBe('Science')
        ->and($employment->start_date->toDateString())->toBe('2021-01-05')
        ->and($employment->end_date)->toBeNull()
        ->and($employment->needsSyncWithOrcid())->toBeFalse();

    $activity = Activity::query()->where('subject_type', $employment->getMorphClass())->where('subject_id', $employment->id)->where('properties->source', 'orcid')->sole();
    expect($activity->event)->toBe('created')
        ->and($activity->description)->toContain('imported from the author\'s ORCID record');
});

test('only employments OSP can import are imported', function (): void {
    $author = Author::factory()->withOrcidConnection()->create();
    syncedEmployment($author, 1);
    syncedEmployment($author, 2)->delete(); // deleted in OSP, waiting to be removed from ORCID

    Saloon::fake([ListAffiliations::class => orcidEmploymentList([
        orcidEmploymentSummary(1),
        orcidEmploymentSummary(2),
        orcidEmploymentSummary(3, ['source' => ['source-client-id' => ['path' => 'APP-SOMEONEELSE00001']]]),
        orcidEmploymentSummary(4, ['source' => ['source-orcid' => ['path' => '0000-0002-1825-0097']]]),
        orcidEmploymentSummary(5, ['organization' => ['name' => 'Elsewhere', 'disambiguated-organization' => ['disambiguated-organization-identifier' => 'https://ror.org/03c4mmv16', 'disambiguation-source' => 'ROR']]]),
        orcidEmploymentSummary(6, ['start-date' => null]),
        orcidEmploymentSummary(7, ['created-date' => ['value' => now()->getTimestampMs()]]), // a push job may not have stored its put-code yet
    ])]);

    expect(resolve(EmploymentReconciler::class)->reconcile($author))->toBe(['imported' => 0, 'deleted' => 0]);
    expect($author->employments()->withTrashed()->pluck('orcid_putcode')->sort()->values()->all())->toBe([1, 2]);
});

test('employments the author deleted on ORCID are deleted in OSP', function (): void {
    $author = Author::factory()->withOrcidConnection()->create();
    $kept = syncedEmployment($author, 1);
    $deletedOnOrcid = syncedEmployment($author, 2);
    Saloon::fake([ListAffiliations::class => orcidEmploymentList([orcidEmploymentSummary(1)])]);

    expect(resolve(EmploymentReconciler::class)->reconcile($author))->toBe(['imported' => 0, 'deleted' => 1]);

    expect(AuthorEmployment::withTrashed()->find($deletedOnOrcid->id))->toBeNull()
        ->and($kept->fresh())->not->toBeNull();

    $activity = Activity::query()->where('subject_type', $deletedOnOrcid->getMorphClass())->where('subject_id', $deletedOnOrcid->id)->where('properties->source', 'orcid')->sole();
    expect($activity->event)->toBe('deleted')
        ->and($activity->description)->toContain('the author deleted it from their ORCID record')
        ->and($activity->properties['orcid_putcode'])->toBe(2);
});

test('employments missing on ORCID are kept while OSP still has to push them', function (): void {
    $author = Author::factory()->withOrcidConnection()->create();
    $edited = syncedEmployment($author, 1);
    $edited->forceFill(['orcid_updated_at' => null])->saveQuietly(); // edited in OSP: the push job recreates it
    $justSynced = syncedEmployment($author, 2);
    $justSynced->forceFill(['updated_at' => now(), 'orcid_updated_at' => now()])->saveQuietly();
    $pendingDelete = syncedEmployment($author, 3);
    $pendingDelete->delete();
    Saloon::fake([ListAffiliations::class => orcidEmploymentList([])]);

    expect(resolve(EmploymentReconciler::class)->reconcile($author))->toBe(['imported' => 0, 'deleted' => 0]);
    expect($author->employments()->withTrashed()->count())->toBe(3);
});

test('opening the ORCID settings refreshes from ORCID, at most once a minute', function (): void {
    $mockClient = Saloon::fake([ListAffiliations::class => orcidEmploymentList([orcidEmploymentSummary(12345)])]);
    $user = connectedUser();

    $this->actingAs($user)->postJson('api/user/orcid/refresh')
        ->assertOk()
        ->assertExactJson(['refreshed' => true, 'employments' => ['imported' => 1, 'deleted' => 0], 'pending' => 0]);

    $this->actingAs($user)->postJson('api/user/orcid/refresh')->assertExactJson(['refreshed' => false]);
    $mockClient->assertSentCount(1);

    Cache::flush();
    $this->actingAs($user)->postJson('api/user/orcid/refresh')->assertJson(['refreshed' => true]);
});

test('refreshing pushes employments still pending, e.g. after their sync failed', function (): void {
    Saloon::fake([ListAffiliations::class => orcidEmploymentList([orcidEmploymentSummary(1)])]);
    $user = connectedUser();
    $failedUpdate = syncedEmployment($user->author, 1);
    $failedUpdate->forceFill(['orcid_updated_at' => null])->saveQuietly();
    $inSync = AuthorEmployment::factory()->create(['author_id' => $user->author->id, 'organization_id' => Organization::getDefaultOrganization()->id]);
    $inSync->forceFill(['orcid_putcode' => 1, 'orcid_updated_at' => now()->addSecond()])->saveQuietly();

    $this->actingAs($user)->postJson('api/user/orcid/refresh')->assertJson(['refreshed' => true, 'pending' => 1]);

    Queue::assertPushed(SyncAuthorEmploymentWithOrcid::class, 1);
    Queue::assertPushed(SyncAuthorEmploymentWithOrcid::class, fn (SyncAuthorEmploymentWithOrcid $job): bool => $job->authorEmployment->is($failedUpdate));
});

test('nothing is pushed when ORCID cannot be read', function (): void {
    Saloon::fake([ListAffiliations::class => MockResponse::make('<html>Service Unavailable</html>', 503)]);
    $user = connectedUser();
    syncedEmployment($user->author, 1)->forceFill(['orcid_updated_at' => null])->saveQuietly();

    $this->actingAs($user)->postJson('api/user/orcid/refresh')->assertExactJson(['refreshed' => false]);

    Queue::assertNotPushed(SyncAuthorEmploymentWithOrcid::class);
    expect($user->author->refresh()->orcid_verified)->toBeTrue();
});

test('refreshing does nothing for an author who is not connected', function (): void {
    $mockClient = Saloon::fake([]);

    $this->actingAs(User::factory()->create())->postJson('api/user/orcid/refresh')->assertExactJson(['refreshed' => false]);

    $mockClient->assertNothingSent();
});

test('a revoked token found while refreshing disconnects the author', function (): void {
    Saloon::fake([ListAffiliations::class => MockResponse::make(['error' => 'invalid_token', 'error_description' => 'Invalid access token'], 401)]);
    $user = connectedUser();

    $this->actingAs($user)->postJson('api/user/orcid/refresh')->assertExactJson(['refreshed' => false]);

    expect($user->author->refresh()->orcid_verified)->toBeFalse();
});

test('the background sync retries ORCID outages', function (): void {
    Saloon::fake([ListAffiliations::class => MockResponse::make('<html>Service Unavailable</html>', 503)]);
    $job = new SyncAuthorFromOrcid(Author::factory()->withOrcidConnection()->create())->withFakeQueueInteractions();

    expect(fn () => $job->handle(resolve(EmploymentReconciler::class)))->toThrow(OrcidServerException::class);
});

test('the background sync skips authors who are not connected', function (): void {
    $mockClient = Saloon::fake([]);
    $job = new SyncAuthorFromOrcid(Author::factory()->create(['orcid_verified' => false]))->withFakeQueueInteractions();

    $job->handle(resolve(EmploymentReconciler::class));

    $job->assertNotFailed();
    $mockClient->assertNothingSent();
});
