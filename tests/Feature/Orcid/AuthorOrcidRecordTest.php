<?php

use App\Enums\PublicationStatus;
use App\Jobs\SyncAuthorFromOrcid;
use App\Models\Author;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use App\Orcid\EmploymentReconciler;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Requests\Affiliations\ListAffiliations;
use VincentAuger\OrcidSdk\Requests\OAuth\RequestClientCredentialsToken;
use VincentAuger\OrcidSdk\Requests\OAuth\RevokeToken;
use VincentAuger\OrcidSdk\Requests\Record\GetRecord;
use VincentAuger\OrcidSdk\Requests\Works\GetWorks;

/**
 * A /read-public client credentials token response
 */
function readPublicTokenResponse(string $token = 'read-public-token'): MockResponse
{
    return MockResponse::make([
        'access_token' => $token,
        'token_type' => 'bearer',
        'refresh_token' => null,
        'expires_in' => 631138518,
        'scope' => '/read-public',
    ]);
}

/**
 * The whole record as returned by the public API
 *
 * @param  array<string, mixed>  $activities
 */
function publicRecordResponse(?string $biography = 'Marine biologist studying salmon.', array $activities = []): MockResponse
{
    return MockResponse::make([
        'orcid-identifier' => ['path' => '0000-0002-1825-0097'],
        'person' => [
            'biography' => $biography === null ? null : ['content' => $biography, 'visibility' => 'public'],
        ],
        'activities-summary' => $activities,
    ]);
}

/**
 * An employment or education summary in the activities section of the record
 *
 * @param  array{0: int, 1?: int}|null  $start  Year and month
 * @param  array{0: int, 1?: int}|null  $end  Year and month
 * @param  array{0: string, 1: string}|null  $disambiguatedOrganization  Identifier and source, e.g. ['https://ror.org/02qa1x782', 'ROR']
 * @return array<string, mixed>
 */
function recordAffiliation(string $organization, ?string $department, ?string $role, ?array $start, ?array $end = null, string $sourceClientId = 'APP-SOMEONE-ELSE', ?array $disambiguatedOrganization = null): array
{
    $date = fn (?array $date): ?array => $date === null ? null : array_filter([
        'year' => ['value' => (string) $date[0]],
        'month' => isset($date[1]) ? ['value' => sprintf('%02d', $date[1])] : null,
    ]);

    return [
        'put-code' => fake()->unique()->numberBetween(1000, 999999),
        'organization' => [
            'name' => $organization,
            'address' => ['city' => 'Ottawa', 'country' => 'CA'],
            'disambiguated-organization' => $disambiguatedOrganization === null ? null : [
                'disambiguated-organization-identifier' => $disambiguatedOrganization[0],
                'disambiguation-source' => $disambiguatedOrganization[1],
            ],
        ],
        'department-name' => $department,
        'role-title' => $role,
        'start-date' => $date($start),
        'end-date' => $date($end),
        'source' => ['source-client-id' => ['path' => $sourceClientId]],
        'visibility' => 'public',
    ];
}

/**
 * @param  list<array<string, mixed>>  $summaries
 * @return array<string, mixed>
 */
function recordAffiliationGroups(string $summaryKey, array $summaries): array
{
    return ['affiliation-group' => array_map(
        fn (array $summary): array => ['summaries' => [[$summaryKey => $summary]]],
        $summaries,
    )];
}

/**
 * A work summary in the activities section of the record
 *
 * @param  array{0: int, 1?: int, 2?: int}|null  $date  Year, month and day
 *                                                      The contributors are only in the full work, read in bulk: the summary in the record leaves them out.
 * @param  list<string>  $dois
 * @param  list<array{0: string|null, 1?: string}>  $contributors  Credit name and ORCID iD
 * @return array<string, mixed>
 */
function recordWork(string $title, ?string $type = 'journal-article', ?array $date = null, ?string $journal = null, array $dois = [], ?string $url = null, ?int $displayIndex = null, array $contributors = []): array
{
    return array_filter([
        'put-code' => fake()->unique()->numberBetween(1000, 999999),
        'title' => ['title' => ['value' => $title]],
        'type' => $type,
        'external-ids' => ['external-id' => array_map(fn (string $doi): array => [
            'external-id-type' => 'doi',
            'external-id-value' => $doi,
            'external-id-relationship' => 'self',
        ], $dois)],
        'journal-title' => $journal === null ? null : ['value' => $journal],
        'publication-date' => $date === null ? null : array_filter([
            'year' => ['value' => (string) $date[0]],
            'month' => isset($date[1]) ? ['value' => sprintf('%02d', $date[1])] : null,
            'day' => isset($date[2]) ? ['value' => sprintf('%02d', $date[2])] : null,
        ]),
        'url' => $url === null ? null : ['value' => $url],
        'display-index' => $displayIndex === null ? null : (string) $displayIndex,
        'visibility' => 'public',
        'contributors' => ['contributor' => array_map(fn (array $contributor): array => array_filter([
            'credit-name' => $contributor[0] === null ? null : ['value' => $contributor[0]],
            'contributor-orcid' => isset($contributor[1]) ? ['path' => $contributor[1], 'host' => 'orcid.org'] : null,
            'contributor-attributes' => ['contributor-role' => 'author'],
        ]), $contributors)],
    ], fn (mixed $value): bool => $value !== null);
}

/**
 * Fake ORCID for a record with these works: the record lists their summaries, and the full works are read in bulk
 * by put-code
 *
 * @param  list<list<array<string, mixed>>>  $groups  The summaries of each group
 */
function fakeOrcidWorks(array $groups): MockClient
{
    $works = collect($groups)->flatten(1)->keyBy('put-code');

    return Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse(activities: recordWorkGroups($groups)),
        GetWorks::class => fn (PendingRequest $request): MockResponse => MockResponse::make(['bulk' => array_map(
            fn (string $putCode): array => ['work' => $works[(int) $putCode]],
            explode(',', Str::afterLast($request->getUrl(), '/works/')),
        )]),
    ]);
}

/**
 * The works section of the record: each group is the same work from one or more sources
 *
 * @param  list<list<array<string, mixed>>>  $groups  The summaries of each group
 * @return array<string, mixed>
 */
function recordWorkGroups(array $groups): array
{
    return ['works' => ['group' => array_map(fn (array $summaries): array => [
        'external-ids' => ['external-id' => array_merge(...array_map(fn (array $summary): array => $summary['external-ids']['external-id'], $summaries))],
        'work-summary' => $summaries,
    ], $groups)]];
}

/**
 * Read the author's ORCID record as a signed-in user
 */
function readOrcidRecord(Author $author, ?User $user = null): TestResponse
{
    return test()->actingAs($user ?? User::factory()->create())->getJson("api/authors/{$author->id}/orcid-record")->assertOk();
}

/**
 * A publication the viewer sees in the author's profile
 */
function authorPublication(Author $author, ?string $doi): Publication
{
    $publication = Publication::factory()->create(['doi' => $doi, 'status' => PublicationStatus::PUBLISHED]);
    PublicationAuthor::factory()->for($publication)->for($author)->create();

    return $publication;
}

test('a signed-in user sees the biography of a connected author', function (): void {
    $mockClient = Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse("Marine biologist.\nStudies <b>salmon</b>."),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();

    $this->actingAs(User::factory()->create())->getJson("api/authors/{$author->id}/orcid-record")
        ->assertOk()
        ->assertExactJson(['data' => [
            'status' => 'available',
            'record' => [
                'biography' => "Marine biologist.\nStudies <b>salmon</b>.",
                'employments' => [],
                'educations' => [],
                'peer_review_count' => 0,
                'works' => [],
                'works_already_listed' => 0,
            ],
        ]]);

    // read on the member API (higher limits) with the read-public token, never with the author's own token
    $mockClient->assertSent(fn (GetRecord $request, $response): bool => str_starts_with((string) $response->getPendingRequest()->getUrl(), 'https://api.orcid.org/v3.0/0000-0002-1825-0097/record')
        && $response->getPendingRequest()->headers()->get('Authorization') === 'Bearer read-public-token');
});

test('the public employments and educations are shown as on ORCID, current first then newest first', function (): void {
    config(['osp.orcid.client_id' => 'APP-OSP']);
    Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse(activities: [
            'employments' => recordAffiliationGroups('employment-summary', [
                recordAffiliation('University of Ottawa', 'Biology', 'Postdoctoral fellow', [2015, 9], [2018, 6]),
                // pushed by OSP: shown like any other employment
                recordAffiliation('Fisheries and Oceans Canada', 'Ecosystems and Oceans Science', 'Research scientist', [2018, 7], sourceClientId: 'APP-OSP', disambiguatedOrganization: ['https://ror.org/02qa1x782', 'ROR']),
                // only a ROR is linked
                recordAffiliation('Ocean Tracking Network', null, 'Associate', [2020], disambiguatedOrganization: ['5682', 'RINGGOLD']),
                recordAffiliation('Dalhousie University', null, null, [2019, 1], [2019, 12]),
            ]),
            'educations' => recordAffiliationGroups('education-summary', [
                recordAffiliation('McGill University', 'Biology', 'BSc', [2006], [2009]),
                recordAffiliation('Dalhousie University', 'Oceanography', 'PhD', [2010, 9], [2015, 5]),
            ]),
        ]),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();

    $this->actingAs(User::factory()->create())->getJson("api/authors/{$author->id}/orcid-record")
        ->assertOk()
        ->assertJsonPath('data.record.employments', [
            ['organization' => 'Ocean Tracking Network', 'ror' => null, 'department' => null, 'role' => 'Associate', 'start_date' => '2020', 'end_date' => null],
            ['organization' => 'Fisheries and Oceans Canada', 'ror' => 'https://ror.org/02qa1x782', 'department' => 'Ecosystems and Oceans Science', 'role' => 'Research scientist', 'start_date' => '2018-07', 'end_date' => null],
            ['organization' => 'Dalhousie University', 'ror' => null, 'department' => null, 'role' => null, 'start_date' => '2019-01', 'end_date' => '2019-12'],
            ['organization' => 'University of Ottawa', 'ror' => null, 'department' => 'Biology', 'role' => 'Postdoctoral fellow', 'start_date' => '2015-09', 'end_date' => '2018-06'],
        ])
        ->assertJsonPath('data.record.educations', [
            ['organization' => 'Dalhousie University', 'ror' => null, 'department' => 'Oceanography', 'role' => 'PhD', 'start_date' => '2010-09', 'end_date' => '2015-05'],
            ['organization' => 'McGill University', 'ror' => null, 'department' => 'Biology', 'role' => 'BSc', 'start_date' => '2006', 'end_date' => '2009'],
        ]);
});

test('the number of public peer reviews is shown, counting a review added by several sources once', function (): void {
    $review = fn (string $journal): array => ['put-code' => fake()->unique()->numberBetween(1000, 999999), 'review-group-id' => $journal, 'visibility' => 'public'];
    Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse(activities: [
            'peer-reviews' => ['group' => [
                ['external-ids' => ['external-id' => [['external-id-type' => 'issn', 'external-id-value' => '0706-652X']]], 'peer-review-group' => [
                    ['peer-review-summary' => [$review('issn:0706-652X'), $review('issn:0706-652X')]],
                    ['peer-review-summary' => [$review('issn:0706-652X')]],
                ]],
                ['external-ids' => ['external-id' => [['external-id-type' => 'issn', 'external-id-value' => '1054-3139']]], 'peer-review-group' => [
                    ['peer-review-summary' => [$review('issn:1054-3139')]],
                ]],
            ]],
        ]),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();

    $this->actingAs(User::factory()->create())->getJson("api/authors/{$author->id}/orcid-record")
        ->assertOk()
        ->assertJsonPath('data.record.peer_review_count', 3);
});

test('the public works are shown one per group, as the version ORCID displays', function (): void {
    fakeOrcidWorks([
        [
            recordWork('Salmon in the wild (Crossref)', dois: ['10.1139/CJFAS-2020-0001'], displayIndex: 0, contributors: [['Someone Else']]),
            recordWork('Salmon in the wild', 'journal-article', [2021, 5, 14], 'Canadian Journal of Fisheries and Aquatic Sciences', ['10.1139/cjfas-2020-0001'], 'https://cdnsciencepub.com/doi/10.1139/cjfas-2020-0001', displayIndex: 1, contributors: [
                ['Josiah Carberry', '0000-0002-1825-0097'],
                ['Jane Doe'],
                // an ORCID iD without a credit name
                [null, '0000-0001-5109-3700'],
                // neither: nothing to show
                [null],
            ]),
        ],
        [recordWork('Herring dataset', 'data-set', [2019], url: 'https://example.org/herring')],
        [recordWork('Ocean notes', type: 'a-type-the-sdk-does-not-know')],
    ]);
    $author = Author::factory()->withOrcidConnection()->create();

    readOrcidRecord($author)
        ->assertJsonPath('data.record.works', [
            ['title' => 'Salmon in the wild', 'type' => 'journal-article', 'date' => '2021-05-14', 'journal' => 'Canadian Journal of Fisheries and Aquatic Sciences', 'doi' => '10.1139/cjfas-2020-0001', 'url' => 'https://cdnsciencepub.com/doi/10.1139/cjfas-2020-0001', 'contributors' => [
                ['name' => 'Josiah Carberry', 'orcid' => 'https://orcid.org/0000-0002-1825-0097'],
                ['name' => 'Jane Doe', 'orcid' => null],
                ['name' => null, 'orcid' => 'https://orcid.org/0000-0001-5109-3700'],
            ]],
            ['title' => 'Herring dataset', 'type' => 'data-set', 'date' => '2019', 'journal' => null, 'doi' => null, 'url' => 'https://example.org/herring', 'contributors' => []],
            ['title' => 'Ocean notes', 'type' => 'other', 'date' => null, 'journal' => null, 'doi' => null, 'url' => null, 'contributors' => []],
        ])
        ->assertJsonPath('data.record.works_already_listed', 0);
});

test('the works are read in full by batches of 100, only for the versions ORCID displays', function (): void {
    $groups = array_map(fn (int $index): array => [recordWork("Work {$index}")], range(1, 150));
    $groups[] = [recordWork('Hidden version', displayIndex: 0), recordWork('Shown version', displayIndex: 1)];
    $mockClient = fakeOrcidWorks($groups);
    $author = Author::factory()->withOrcidConnection()->create();

    readOrcidRecord($author)->assertJsonCount(151, 'data.record.works');

    $mockClient->assertSentCount(2, GetWorks::class);
    $mockClient->assertNotSent(fn (GetWorks $request, $response): bool => str_contains((string) $response->getPendingRequest()->getUrl(), (string) $groups[150][0]['put-code']));
});

test('the works are still shown, without contributors, when ORCID cannot read them in full', function (): void {
    $mockClient = fakeOrcidWorks([[recordWork('Salmon in the wild', contributors: [['Josiah Carberry']])]]);
    $mockClient->addResponses([GetWorks::class => MockResponse::make(['error' => 'down'], 503)]);
    $author = Author::factory()->withOrcidConnection()->create();

    readOrcidRecord($author)
        ->assertJsonPath('data.status', 'available')
        ->assertJsonPath('data.record.works.0.title', 'Salmon in the wild')
        ->assertJsonPath('data.record.works.0.contributors', []);
});

test('no works are read in full when the record has none', function (): void {
    $mockClient = fakeOrcidWorks([]);
    $author = Author::factory()->withOrcidConnection()->create();

    readOrcidRecord($author)->assertJsonPath('data.record.works', []);

    $mockClient->assertNotSent(GetWorks::class);
});

test('a work whose DOI is one of the author\'s publications is left out and counted', function (string $orcidDoi, string $ospDoi): void {
    fakeOrcidWorks([
        [recordWork('Salmon in the wild', dois: [$orcidDoi])],
        [recordWork('Herring dataset', 'data-set', dois: ['10.5281/zenodo.123'])],
    ]);
    $author = Author::factory()->withOrcidConnection()->create();
    authorPublication($author, $ospDoi);

    $response = readOrcidRecord($author)->assertJsonPath('data.record.works_already_listed', 1);
    expect($response->json('data.record.works'))->sequence(
        fn ($work) => $work->title->toBe('Herring dataset'),
    );
})->with([
    'same bare DOI' => ['10.1139/cjfas-2020-0001', '10.1139/cjfas-2020-0001'],
    'different case' => ['10.1139/CJFAS-2020-0001', 'https://doi.org/10.1139/cjfas-2020-0001'],
    'resolver URL on ORCID' => ['https://doi.org/10.1139/cjfas-2020-0001', 'https://doi.org/10.1139/CJFAS-2020-0001'],
    'old resolver on ORCID' => ['http://dx.doi.org/10.1139/cjfas-2020-0001', '10.1139/cjfas-2020-0001'],
    'doi: URI on ORCID' => ['doi:10.1139/cjfas-2020-0001', 'https://doi.org/10.1139/cjfas-2020-0001'],
    'www resolver on ORCID' => ['https://www.doi.org/10.1139/cjfas-2020-0001', '10.1139/cjfas-2020-0001'],
    'resolver without scheme, with spaces' => [' doi.org/10.1139/cjfas-2020-0001 ', 'https://doi.org/10.1139/cjfas-2020-0001'],
]);

test('a work stays listed when the viewer cannot see the author\'s publication with its DOI', function (): void {
    fakeOrcidWorks([[recordWork('Salmon in the wild', dois: ['10.1139/cjfas-2020-0001'])]]);
    $author = Author::factory()->withOrcidConnection()->create();
    $publication = authorPublication($author, 'https://doi.org/10.1139/cjfas-2020-0001');
    $publication->update(['status' => PublicationStatus::ACCEPTED]);

    readOrcidRecord($author)
        ->assertJsonPath('data.record.works.0.title', 'Salmon in the wild')
        ->assertJsonPath('data.record.works_already_listed', 0);

    readOrcidRecord($author, User::factory()->editor()->create())
        ->assertJsonPath('data.record.works', [])
        ->assertJsonPath('data.record.works_already_listed', 1);
});

test('a work is left out when any version in its group has the DOI of one of the author\'s publications', function (): void {
    fakeOrcidWorks([[
        recordWork('Salmon in the wild', displayIndex: 1),
        recordWork('Salmon in the wild (Crossref)', dois: ['10.1139/cjfas-2020-0001'], displayIndex: 0),
    ]]);
    $author = Author::factory()->withOrcidConnection()->create();
    authorPublication($author, 'https://doi.org/10.1139/cjfas-2020-0001');

    readOrcidRecord($author)
        ->assertJsonPath('data.record.works', [])
        ->assertJsonPath('data.record.works_already_listed', 1);
});

test('works without a DOI, or with the DOI of another author\'s publication, are always shown', function (): void {
    fakeOrcidWorks([
        // same title as an OSP publication, but no DOI: never matched by title
        [recordWork('Salmon in the wild')],
        [recordWork('Herring dataset', 'data-set', dois: ['10.5281/zenodo.123'])],
    ]);
    $author = Author::factory()->withOrcidConnection()->create();
    $publication = authorPublication($author, 'https://doi.org/10.1139/cjfas-2020-0001');
    $publication->update(['title' => 'Salmon in the wild']);
    authorPublication($author, null);
    authorPublication(Author::factory()->create(), 'https://doi.org/10.5281/zenodo.123');

    $response = readOrcidRecord($author)->assertJsonPath('data.record.works_already_listed', 0);
    expect($response->json('data.record.works.*.title'))->toBe(['Salmon in the wild', 'Herring dataset']);
});

test('nothing is read from ORCID for an author who is not connected or hides their record', function (Author $author): void {
    $mockClient = Saloon::fake([]);

    $this->actingAs(User::factory()->create())->getJson("api/authors/{$author->id}/orcid-record")
        ->assertOk()
        ->assertExactJson(['data' => ['status' => 'empty', 'record' => null]]);

    $mockClient->assertNothingSent();
})->with([
    'never connected' => fn (): Author => Author::factory()->create(['orcid' => 'https://orcid.org/0000-0002-1825-0097']),
    'disconnected' => fn (): Author => tap(Author::factory()->withOrcidConnection()->create())->clearOrcidToken(),
    'expired token' => fn (): Author => Author::factory()->withOrcidConnection()->create(['orcid_expires_at' => now()->subDay()]),
    'record hidden' => fn (): Author => Author::factory()->withOrcidConnection()->create(['show_orcid_record' => false]),
]);

test('the record is read from ORCID at most once an hour, and the token is reused', function (): void {
    $mockClient = Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse(),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();
    $otherAuthor = Author::factory()->withOrcidConnection()->create();
    $user = User::factory()->create();

    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")->assertOk();
    $this->travel(59)->minutes();
    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")->assertOk();
    $this->actingAs($user)->getJson("api/authors/{$otherAuthor->id}/orcid-record")->assertOk();

    $mockClient->assertSentCount(1, RequestClientCredentialsToken::class);
    $mockClient->assertSentCount(2, GetRecord::class);
});

test('after an hour the cached record is served while it is refreshed in the background', function (): void {
    // run the refresh deferred after the response right away
    $this->withoutDefer();
    Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse('Old biography'),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();
    $user = User::factory()->create();
    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")->assertOk();

    Saloon::fake([GetRecord::class => publicRecordResponse('New biography')]);
    $this->travel(2)->hours();

    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")
        ->assertJsonPath('data.record.biography', 'Old biography');
    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")
        ->assertJsonPath('data.record.biography', 'New biography');
});

test('a cached record is still served for up to a week while ORCID is down', function (): void {
    Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse(),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();
    $user = User::factory()->create();
    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")->assertOk();

    Saloon::fake([GetRecord::class => MockResponse::make(['error' => 'down'], 503)]);

    $this->travel(6)->days();
    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")
        ->assertOk()
        ->assertJsonPath('data.status', 'available')
        ->assertJsonPath('data.record.biography', 'Marine biologist studying salmon.');

    $this->travel(2)->days();
    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")
        ->assertOk()
        ->assertExactJson(['data' => ['status' => 'unavailable', 'record' => null]]);
});

test('while ORCID is down a stale record is refreshed at most once an hour', function (): void {
    $this->withoutDefer();
    $mockClient = Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse(),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();
    $user = User::factory()->create();
    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")->assertOk();

    Saloon::fake([GetRecord::class => MockResponse::make(['error' => 'down'], 503)]);
    $this->travel(2)->hours();

    foreach (range(1, 3) as $view) {
        $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")
            ->assertOk()
            ->assertJsonPath('data.record.biography', 'Marine biologist studying salmon.');
        $this->travel(10)->minutes();
    }
    $mockClient->assertSentCount(2, GetRecord::class);

    $this->travel(1)->hour();
    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")->assertJsonPath('data.status', 'available');
    $mockClient->assertSentCount(3, GetRecord::class);
});

test('the record is unavailable when ORCID cannot be reached and nothing is cached', function (MockResponse $failure): void {
    Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => $failure,
    ]);
    $author = Author::factory()->withOrcidConnection()->create();

    $this->actingAs(User::factory()->create())->getJson("api/authors/{$author->id}/orcid-record")
        ->assertOk()
        ->assertExactJson(['data' => ['status' => 'unavailable', 'record' => null]]);
})->with([
    'server error' => fn (): MockResponse => MockResponse::make(['error' => 'down'], 503),
    'rate limited' => fn (): MockResponse => MockResponse::make(['error' => 'slow down'], 429),
    'record not found' => fn (): MockResponse => MockResponse::make(['error' => 'not found'], 404),
]);

test('a rejected read-public token is discarded and requested again', function (): void {
    $tokens = ['revoked-token', 'new-token'];
    $mockClient = Saloon::fake([
        RequestClientCredentialsToken::class => function () use (&$tokens): MockResponse {
            return readPublicTokenResponse(array_shift($tokens));
        },
        GetRecord::class => fn (PendingRequest $request): MockResponse => $request->headers()->get('Authorization') === 'Bearer new-token'
            ? publicRecordResponse()
            : MockResponse::make(['error' => 'invalid_token'], 401),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();

    $this->actingAs(User::factory()->create())->getJson("api/authors/{$author->id}/orcid-record")
        ->assertJsonPath('data.status', 'available');

    $mockClient->assertSentCount(2, RequestClientCredentialsToken::class);
    $mockClient->assertSentCount(2, GetRecord::class);
});

test('a refused token request is not retried', function (): void {
    $mockClient = Saloon::fake([
        RequestClientCredentialsToken::class => MockResponse::make(['error' => 'invalid_client'], 401),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();

    $this->actingAs(User::factory()->create())->getJson("api/authors/{$author->id}/orcid-record")
        ->assertJsonPath('data.status', 'unavailable');

    $mockClient->assertSentCount(1, RequestClientCredentialsToken::class);
});

test('a new read-public token is requested only once when ORCID keeps refusing it', function (): void {
    $mockClient = Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => MockResponse::make(['error' => 'invalid_token'], 401),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();

    $this->actingAs(User::factory()->create())->getJson("api/authors/{$author->id}/orcid-record")
        ->assertExactJson(['data' => ['status' => 'unavailable', 'record' => null]]);

    $mockClient->assertSentCount(2, RequestClientCredentialsToken::class);
    $mockClient->assertSentCount(2, GetRecord::class);
});

test('authors show their ORCID record by default', function (): void {
    $this->actingAs(User::factory()->create())->postJson('api/authors', [
        'first_name' => 'Josiah',
        'last_name' => 'Carberry',
        'email' => 'josiah.carberry@example.com',
        'is_independent' => true,
    ])->assertCreated()->assertJsonPath('data.show_orcid_record', true);
});

test('the author or an editor can hide the ORCID record, and the change is logged', function (bool $byEditor): void {
    $this->freezeTime();
    $mockClient = Saloon::fake([]);
    $user = User::factory()->create();
    $user->author->forceFill(Author::factory()->withOrcidConnection()->raw(['email' => $user->email]))->save();
    $actor = $byEditor ? User::factory()->editor()->create() : $user;

    $this->actingAs($actor)->putJson("api/authors/{$user->author->id}", ['show_orcid_record' => false])
        ->assertOk()
        ->assertJsonPath('data.show_orcid_record', false);

    $this->actingAs($actor)->getJson("api/authors/{$user->author->id}/orcid-record")
        ->assertJsonPath('data.status', 'empty');
    $mockClient->assertNothingSent();

    $activity = Activity::query()->forSubject($user->author)->causedBy($actor)->where('event', 'updated')->sole();
    expect($activity->attribute_changes['attributes'])->toBe(['show_orcid_record' => false]);
})->with(['by the author' => false, 'by an editor' => true]);

test('a DFO author who has not filled their sector and region can still hide their ORCID record', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->putJson("api/authors/{$user->author->id}", ['show_orcid_record' => false])
        ->assertOk()
        ->assertJsonPath('data.show_orcid_record', false);
});

test('other users cannot change whether an author shows their ORCID record', function (): void {
    $user = User::factory()->create();

    $this->actingAs(User::factory()->create())->putJson("api/authors/{$user->author->id}", ['show_orcid_record' => false])
        ->assertForbidden();

    expect($user->author->refresh()->show_orcid_record)->toBeTrue();
});

test('the cached record is cleared when the author disconnects, ORCID revokes the token or the author refreshes from ORCID', function (Closure $clear): void {
    Queue::fake();
    Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse('Old biography'),
    ]);
    $user = User::factory()->create();
    $user->author->forceFill(Author::factory()->withOrcidConnection()->raw(['email' => $user->email]))->save();
    $this->actingAs($user)->getJson("api/authors/{$user->author->id}/orcid-record")->assertJsonPath('data.record.biography', 'Old biography');

    $clear->call($this, $user);

    // the author connects again
    $user->author->forceFill(Author::factory()->withOrcidConnection()->raw(['email' => $user->email]))->save();
    Saloon::fake([GetRecord::class => publicRecordResponse('New biography')]);

    $this->actingAs($user)->getJson("api/authors/{$user->author->id}/orcid-record")
        ->assertJsonPath('data.record.biography', 'New biography');
})->with([
    'disconnect' => function (User $user): void {
        Saloon::fake([RevokeToken::class => MockResponse::make('', 200)]);
        $this->actingAs($user)->postJson('api/user/orcid/revoke')->assertOk();
    },
    'token revoked on ORCID' => function (User $user): void {
        Saloon::fake([ListAffiliations::class => MockResponse::make(['error' => 'invalid_token'], 401)]);
        new SyncAuthorFromOrcid($user->author)->withFakeQueueInteractions()->handle(resolve(EmploymentReconciler::class));
        expect($user->author->refresh()->orcid_access_token)->toBeNull();
    },
    'refresh from ORCID' => function (User $user): void {
        Saloon::fake([ListAffiliations::class => MockResponse::make(['affiliation-group' => []])]);
        $this->actingAs($user)->postJson('api/user/orcid/refresh')->assertJsonPath('refreshed', true);
    },
]);

test('a record cached for a previous ORCID iD is not shown', function (): void {
    $mockClient = Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse('Previous iD biography'),
    ]);
    $author = Author::factory()->withOrcidConnection()->create();
    $user = User::factory()->create();
    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")->assertOk();

    // e.g. a background refresh finished after the author reconnected with another iD
    $mockClient->addResponses([GetRecord::class => publicRecordResponse('New iD biography')]);
    $author->update(['orcid' => 'https://orcid.org/0000-0001-5109-3700']);

    $this->actingAs($user)->getJson("api/authors/{$author->id}/orcid-record")
        ->assertJsonPath('data.record.biography', 'New iD biography');
    $mockClient->assertSent(fn (GetRecord $request, $response): bool => str_contains((string) $response->getPendingRequest()->getUrl(), '0000-0001-5109-3700'));
});

test('hiding the ORCID record keeps the cached record', function (): void {
    $mockClient = Saloon::fake([
        RequestClientCredentialsToken::class => readPublicTokenResponse(),
        GetRecord::class => publicRecordResponse(),
    ]);
    $user = User::factory()->create();
    $user->author->forceFill(Author::factory()->withOrcidConnection()->raw(['email' => $user->email]))->save();
    $this->actingAs($user)->getJson("api/authors/{$user->author->id}/orcid-record")->assertOk();

    $this->actingAs($user)->putJson("api/authors/{$user->author->id}", ['show_orcid_record' => false])->assertOk();
    $this->actingAs($user)->putJson("api/authors/{$user->author->id}", ['show_orcid_record' => true])->assertOk();

    $this->actingAs($user)->getJson("api/authors/{$user->author->id}/orcid-record")->assertJsonPath('data.status', 'available');
    $mockClient->assertSentCount(1, GetRecord::class);
});

test('guests cannot read an author\'s ORCID record', function (): void {
    $author = Author::factory()->withOrcidConnection()->create();

    $this->getJson("api/authors/{$author->id}/orcid-record")->assertUnauthorized();
});
