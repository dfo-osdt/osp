<?php

use App\Jobs\SyncAuthorFromOrcid;
use App\Models\Author;
use App\Models\User;
use App\Orcid\EmploymentReconciler;
use Illuminate\Support\Facades\Queue;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Requests\Affiliations\ListAffiliations;
use VincentAuger\OrcidSdk\Requests\OAuth\RequestClientCredentialsToken;
use VincentAuger\OrcidSdk\Requests\OAuth\RevokeToken;
use VincentAuger\OrcidSdk\Requests\Record\GetRecord;

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
 */
function publicRecordResponse(?string $biography = 'Marine biologist studying salmon.'): MockResponse
{
    return MockResponse::make([
        'orcid-identifier' => ['path' => '0000-0002-1825-0097'],
        'person' => [
            'biography' => $biography === null ? null : ['content' => $biography, 'visibility' => 'public'],
        ],
        'activities-summary' => [],
    ]);
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
            'record' => ['biography' => "Marine biologist.\nStudies <b>salmon</b>."],
        ]]);

    // read on the public API with the read-public token, never with the author's own token
    $mockClient->assertSent(fn (GetRecord $request, $response): bool => str_starts_with((string) $response->getPendingRequest()->getUrl(), 'https://pub.orcid.org/v3.0/0000-0002-1825-0097/record')
        && $response->getPendingRequest()->headers()->get('Authorization') === 'Bearer read-public-token');
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
