<?php

use App\Models\Author;
use App\Models\Organization;
use App\Models\User;
use App\Orcid\OrcidClientFactory;
use Illuminate\Testing\TestResponse;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use VincentAuger\OrcidSdk\Requests\OAuth\RequestClientCredentialsToken;
use VincentAuger\OrcidSdk\Requests\Record\GetRecord;
use VincentAuger\OrcidSdk\Requests\Search\ExpandedSearch;

/**
 * An expanded search page as returned by ORCID
 *
 * @param  list<array<string, mixed>>  $results
 */
function expandedSearchResponse(array $results, ?int $numFound = null): MockResponse
{
    return MockResponse::make([
        'expanded-result' => $results === [] ? null : $results,
        'num-found' => $numFound ?? count($results),
    ]);
}

/**
 * One record found by an expanded search
 *
 * @param  list<string>  $emails
 * @param  list<string>  $institutions
 * @param  list<string>  $otherNames
 * @return array<string, mixed>
 */
function expandedSearchResult(string $orcid, string $givenNames, string $familyName, array $emails = [], array $institutions = [], ?string $creditName = null, array $otherNames = []): array
{
    return [
        'orcid-id' => $orcid,
        'given-names' => $givenNames,
        'family-names' => $familyName,
        'credit-name' => $creditName,
        'other-name' => $otherNames,
        'email' => $emails,
        'institution-name' => $institutions,
    ];
}

/**
 * The whole public record of someone to import as an author
 *
 * @param  list<array{0: string, 1?: bool}>  $emails  Address and whether it is the primary one
 * @param  list<array<string, mixed>>  $employments  As recordAffiliation() builds them
 */
function authorRecordResponse(string $orcid = '0000-0002-1825-0097', ?string $givenNames = 'Josiah', ?string $familyName = 'Carberry', ?string $creditName = null, array $emails = [], array $employments = []): MockResponse
{
    return MockResponse::make([
        'orcid-identifier' => ['path' => $orcid],
        'person' => [
            'name' => [
                'given-names' => $givenNames === null ? null : ['value' => $givenNames],
                'family-name' => $familyName === null ? null : ['value' => $familyName],
                'credit-name' => $creditName === null ? null : ['value' => $creditName],
                'visibility' => 'public',
            ],
            'emails' => ['email' => array_map(fn (array $email): array => [
                'email' => $email[0],
                'primary' => $email[1] ?? false,
                'verified' => true,
                'visibility' => 'public',
            ], $emails)],
        ],
        'activities-summary' => [
            'employments' => ['affiliation-group' => array_map(
                fn (array $employment): array => ['summaries' => [['employment-summary' => $employment]]],
                $employments,
            )],
        ],
    ]);
}

/**
 * An employment summary in the activities section of the record
 *
 * @param  array{0: int, 1?: int}|null  $start  Year and month
 * @param  array{0: int, 1?: int}|null  $end  Year and month
 * @return array<string, mixed>
 */
function lookupEmployment(string $organization, ?array $start, ?array $end = null, ?string $ror = null): array
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
            'disambiguated-organization' => $ror === null ? null : [
                'disambiguated-organization-identifier' => $ror,
                'disambiguation-source' => 'ROR',
            ],
        ],
        'start-date' => $date($start),
        'end-date' => $date($end),
        'visibility' => 'public',
    ];
}

function lookupTokenResponse(): MockResponse
{
    return MockResponse::make(['access_token' => 'read-public-token', 'token_type' => 'bearer', 'expires_in' => 631138518, 'scope' => '/read-public']);
}

function fakeOrcidSearch(MockResponse $search): MockClient
{
    return Saloon::fake([
        RequestClientCredentialsToken::class => lookupTokenResponse(),
        ExpandedSearch::class => $search,
    ]);
}

function fakeOrcidAuthorRecord(MockResponse $record): MockClient
{
    return Saloon::fake([
        RequestClientCredentialsToken::class => lookupTokenResponse(),
        GetRecord::class => $record,
    ]);
}

function searchOrcid(string $query, string $locale = 'en'): TestResponse
{
    return test()->actingAs(User::factory()->create())
        ->withHeader('Accept-Language', $locale)
        ->getJson('api/orcid/author-search?'.http_build_query(['q' => $query]));
}

function previewOrcidAuthor(string $orcid): TestResponse
{
    return test()->actingAs(User::factory()->create())->getJson("api/orcid/author-preview/{$orcid}");
}

describe('searching ORCID for an author to add', function (): void {
    test('the matching records are listed with what helps tell people apart', function (): void {
        fakeOrcidSearch(expandedSearchResponse([
            expandedSearchResult('0000-0002-1825-0097', 'Josiah', 'Carberry', ['josiah@brown.edu'], ['Brown University', 'Wesleyan University'], 'J. S. Carberry', ['Jos Carberry']),
            expandedSearchResult('0000-0001-5109-3700', 'Josie', 'Carberry'),
        ], numFound: 2));

        searchOrcid('Josiah Carberry')
            ->assertOk()
            ->assertExactJson(['data' => [
                'status' => 'available',
                'num_found' => 2,
                'orcid_search_url' => 'https://orcid.org/orcid-search/search?searchQuery=Josiah%20Carberry&lang=en',
                'results' => [
                    [
                        'orcid' => 'https://orcid.org/0000-0002-1825-0097',
                        'name' => 'J. S. Carberry',
                        'given_names' => 'Josiah',
                        'family_name' => 'Carberry',
                        'other_names' => ['Jos Carberry'],
                        'emails' => ['josiah@brown.edu'],
                        'institutions' => ['Brown University', 'Wesleyan University'],
                        'existing_author_id' => null,
                    ],
                    [
                        'orcid' => 'https://orcid.org/0000-0001-5109-3700',
                        'name' => 'Josie Carberry',
                        'given_names' => 'Josie',
                        'family_name' => 'Carberry',
                        'other_names' => [],
                        'emails' => [],
                        'institutions' => [],
                        'existing_author_id' => null,
                    ],
                ],
            ]]);
    });

    test('a name is searched in every name of the record, ten records at a time', function (string $query, string $solrQuery): void {
        $mockClient = fakeOrcidSearch(expandedSearchResponse([]));

        searchOrcid($query)->assertOk()->assertJsonPath('data.results', []);

        $mockClient->assertSent(fn (ExpandedSearch $request): bool => $request->query()->get('q') === $solrQuery
            && $request->query()->get('rows') === 10);
    })->with([
        'given then family name' => ['Josiah Carberry', 'given-and-family-names:(Josiah AND Carberry) OR credit-name:(Josiah AND Carberry) OR other-names:(Josiah AND Carberry)'],
        'family name, given name' => ['Carberry, Josiah', 'given-and-family-names:(Carberry AND Josiah) OR credit-name:(Carberry AND Josiah) OR other-names:(Carberry AND Josiah)'],
        'accents and extra spaces' => ['  Hélène   Côté ', 'given-and-family-names:(Hélène AND Côté) OR credit-name:(Hélène AND Côté) OR other-names:(Hélène AND Côté)'],
        'search syntax is taken literally' => ['O\'Neil-Smith (Jr)', 'given-and-family-names:(O\'Neil\-Smith AND \(Jr\)) OR credit-name:(O\'Neil\-Smith AND \(Jr\)) OR other-names:(O\'Neil\-Smith AND \(Jr\))'],
    ]);

    test('an ORCID iD is looked up directly, bare or as a link', function (string $query): void {
        $mockClient = fakeOrcidSearch(expandedSearchResponse([expandedSearchResult('0000-0002-1825-0097', 'Josiah', 'Carberry')]));

        searchOrcid($query)->assertOk()->assertJsonPath('data.results.0.orcid', 'https://orcid.org/0000-0002-1825-0097');

        $mockClient->assertSent(fn (ExpandedSearch $request): bool => $request->query()->get('q') === 'orcid:0000-0002-1825-0097');
    })->with([
        'bare' => ['0000-0002-1825-0097'],
        'link' => ['https://orcid.org/0000-0002-1825-0097'],
        'sandbox link' => ['https://sandbox.orcid.org/0000-0002-1825-0097'],
    ]);

    test('a record already in OSP points to the existing author', function (): void {
        $author = Author::factory()->create(['orcid' => 'https://orcid.org/0000-0002-1825-0097']);
        fakeOrcidSearch(expandedSearchResponse([
            expandedSearchResult('0000-0002-1825-0097', 'Josiah', 'Carberry'),
            expandedSearchResult('0000-0001-5109-3700', 'Josie', 'Carberry'),
        ]));

        searchOrcid('Carberry')
            ->assertJsonPath('data.results.0.existing_author_id', $author->id)
            ->assertJsonPath('data.results.1.existing_author_id', null);
    });

    test('the link to search on orcid.org follows the language of the page', function (): void {
        fakeOrcidSearch(expandedSearchResponse([]));

        searchOrcid('Hélène Côté', 'fr')
            ->assertJsonPath('data.orcid_search_url', 'https://orcid.org/orcid-search/search?searchQuery=H%C3%A9l%C3%A8ne%20C%C3%B4t%C3%A9&lang=fr');
    });

    test('the same search is answered from the cache for ten minutes', function (): void {
        $mockClient = fakeOrcidSearch(expandedSearchResponse([expandedSearchResult('0000-0002-1825-0097', 'Josiah', 'Carberry')]));

        searchOrcid('Josiah Carberry')->assertOk();
        $this->travel(9)->minutes();
        searchOrcid('josiah  carberry')->assertJsonPath('data.results.0.name', 'Josiah Carberry');
        $mockClient->assertSentCount(1, ExpandedSearch::class);

        $this->travel(2)->minutes();
        searchOrcid('Josiah Carberry')->assertOk();
        $mockClient->assertSentCount(2, ExpandedSearch::class);
    });

    test('an author found since a search was cached is still pointed to', function (): void {
        fakeOrcidSearch(expandedSearchResponse([expandedSearchResult('0000-0002-1825-0097', 'Josiah', 'Carberry')]));
        searchOrcid('Carberry')->assertJsonPath('data.results.0.existing_author_id', null);

        $author = Author::factory()->create(['orcid' => 'https://orcid.org/0000-0002-1825-0097']);

        searchOrcid('Carberry')->assertJsonPath('data.results.0.existing_author_id', $author->id);
    });

    test('ORCID being unavailable still offers to search on orcid.org', function (MockResponse $failure): void {
        fakeOrcidSearch($failure);

        searchOrcid('Josiah Carberry')
            ->assertOk()
            ->assertExactJson(['data' => [
                'status' => 'unavailable',
                'num_found' => 0,
                'orcid_search_url' => 'https://orcid.org/orcid-search/search?searchQuery=Josiah%20Carberry&lang=en',
                'results' => [],
            ]]);
    })->with([
        'server error' => fn (): MockResponse => MockResponse::make(['error' => 'down'], 503),
        'rate limited' => fn (): MockResponse => MockResponse::make(['error' => 'slow down'], 429),
    ]);

    test('the search needs at least two characters', function (string $query): void {
        Saloon::fake([]);

        searchOrcid($query)->assertUnprocessable()->assertJsonValidationErrors('q');
    })->with(['empty' => [''], 'one character' => ['J'], 'only spaces' => ['   ']]);

    test('the sandbox is searched when OSP uses it', function (): void {
        config(['osp.orcid.use_sandbox' => true]);
        app()->forgetInstance(OrcidClientFactory::class);
        $mockClient = fakeOrcidSearch(expandedSearchResponse([expandedSearchResult('0000-0002-1825-0097', 'Josiah', 'Carberry')]));

        searchOrcid('Carberry')
            ->assertJsonPath('data.results.0.orcid', 'https://sandbox.orcid.org/0000-0002-1825-0097')
            ->assertJsonPath('data.orcid_search_url', 'https://sandbox.orcid.org/orcid-search/search?searchQuery=Carberry&lang=en');

        $mockClient->assertSent(fn (ExpandedSearch $request, $response): bool => str_starts_with((string) $response->getPendingRequest()->getUrl(), 'https://api.sandbox.orcid.org/'));
    });
});

describe('previewing what an ORCID record fills in', function (): void {
    test('the names, public emails and current employments are given ready for the author form', function (): void {
        $laval = Organization::factory()->create(['ror_identifier' => 'https://ror.org/04sjchr03']);
        fakeOrcidAuthorRecord(authorRecordResponse(
            creditName: 'J. S. Carberry',
            emails: [['josiah@gmail.com'], ['Josiah.Carberry@ulaval.ca', true]],
            employments: [
                lookupEmployment('Brown University', [2015, 9]),
                lookupEmployment('Université Laval', [2020, 1], ror: 'https://ror.org/04sjchr03/'),
                lookupEmployment('Wesleyan University', [2005], [2015, 8]),
            ],
        ));

        previewOrcidAuthor('0000-0002-1825-0097')
            ->assertOk()
            ->assertExactJson(['data' => [
                'status' => 'available',
                'author' => [
                    'orcid' => 'https://orcid.org/0000-0002-1825-0097',
                    'first_name' => 'Josiah',
                    'last_name' => 'Carberry',
                    'credit_name' => 'J. S. Carberry',
                    'emails' => ['josiah.carberry@ulaval.ca', 'josiah@gmail.com'],
                    'affiliations' => [
                        ['name' => 'Université Laval', 'organization_id' => $laval->id, 'start_date' => '2020-01', 'suggested' => true],
                        ['name' => 'Brown University', 'organization_id' => null, 'start_date' => '2015-09', 'suggested' => false],
                    ],
                    'existing_author_id' => null,
                ],
            ]]);
    });

    test('without an employment OSP knows, the newest one is suggested', function (): void {
        fakeOrcidAuthorRecord(authorRecordResponse(employments: [
            lookupEmployment('Brown University', [2015, 9], ror: 'https://ror.org/05gq02987'),
            lookupEmployment('Université Laval', [2021]),
        ]));

        previewOrcidAuthor('0000-0002-1825-0097')
            ->assertJsonPath('data.author.affiliations.0', ['name' => 'Université Laval', 'organization_id' => null, 'start_date' => '2021', 'suggested' => true])
            ->assertJsonPath('data.author.affiliations.1.suggested', false);
    });

    test('a record with nothing public to fill in still previews', function (): void {
        fakeOrcidAuthorRecord(authorRecordResponse(givenNames: null, familyName: null));

        previewOrcidAuthor('0000-0002-1825-0097')
            ->assertJsonPath('data.status', 'available')
            ->assertJsonPath('data.author.first_name', null)
            ->assertJsonPath('data.author.last_name', null)
            ->assertJsonPath('data.author.emails', [])
            ->assertJsonPath('data.author.affiliations', []);
    });

    test('someone already in OSP points to the existing author, by ORCID iD or by email', function (array $existing): void {
        $author = Author::factory()->create($existing);
        fakeOrcidAuthorRecord(authorRecordResponse(emails: [['Josiah.Carberry@brown.edu']]));

        previewOrcidAuthor('0000-0002-1825-0097')->assertJsonPath('data.author.existing_author_id', $author->id);
    })->with([
        'same iD' => [['orcid' => 'https://orcid.org/0000-0002-1825-0097']],
        'same email' => [['email' => 'josiah.carberry@brown.edu']],
    ]);

    test('the record is read at most once a day', function (): void {
        $mockClient = fakeOrcidAuthorRecord(authorRecordResponse());

        previewOrcidAuthor('0000-0002-1825-0097')->assertOk();
        $this->travel(23)->hours();
        previewOrcidAuthor('0000-0002-1825-0097')->assertJsonPath('data.author.first_name', 'Josiah');
        $mockClient->assertSentCount(1, GetRecord::class);

        $this->travel(2)->hours();
        previewOrcidAuthor('0000-0002-1825-0097')->assertOk();
        $mockClient->assertSentCount(2, GetRecord::class);
    });

    test('the record is unavailable when ORCID cannot be read', function (MockResponse $failure): void {
        fakeOrcidAuthorRecord($failure);

        previewOrcidAuthor('0000-0002-1825-0097')
            ->assertOk()
            ->assertExactJson(['data' => ['status' => 'unavailable', 'author' => null]]);
    })->with([
        'server error' => fn (): MockResponse => MockResponse::make(['error' => 'down'], 503),
        'record not found' => fn (): MockResponse => MockResponse::make(['error' => 'not found'], 404),
    ]);

    test('only a valid ORCID iD can be previewed', function (string $orcid): void {
        $mockClient = Saloon::fake([]);

        previewOrcidAuthor($orcid)->assertNotFound();

        $mockClient->assertNothingSent();
    })->with(['bad checksum' => ['0000-0002-1825-0098'], 'not an iD' => ['carberry']]);
});

test('guests cannot search ORCID or preview a record', function (): void {
    Saloon::fake([]);

    $this->getJson('api/orcid/author-search?q=Carberry')->assertUnauthorized();
    $this->getJson('api/orcid/author-preview/0000-0002-1825-0097')->assertUnauthorized();
});
