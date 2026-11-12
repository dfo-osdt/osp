<?php

use App\Enums\OrcidSyncIntent;
use App\Enums\Permissions\UserRole;
use App\Enums\PublicationStatus;
use App\Models\Journal;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Requests\Works\CreateWork;
use VincentAuger\OrcidSdk\Requests\Works\DeleteWork;

/**
 * The given user's author on a published report with a DOI in a flagged DFO series
 *
 * @param  array<string, mixed>  $journal
 * @param  array<string, mixed>  $publication
 */
function authorWork(User $user, array $journal = [], array $publication = []): PublicationAuthor
{
    return PublicationAuthor::factory()->create([
        'author_id' => $user->author->id,
        'publication_id' => Publication::factory()->published()->create([
            'title' => 'Stock assessment of Atlantic cod',
            'doi' => '10.60825/abc-123',
            'published_on' => '2025-03-14',
            'journal_id' => Journal::factory()->dfoSeries()->create([
                'title' => 'DFO Can. Sci. Advis. Sec. Res. Doc.',
                'issn' => '1919-5079',
                'orcid_sync_enabled' => true,
                ...$journal,
            ])->id,
            ...$publication,
        ])->id,
    ]);
}

test('an author can list their publications that can go on ORCID', function (): void {
    $user = User::factory()->create();
    $work = authorWork($user);

    $this->actingAs($user)->getJson('api/user/orcid/works')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.data.id', $work->id)
        ->assertJsonPath('data.0.data.orcid_status', 'not_on_orcid')
        ->assertJsonPath('data.0.data.publication.id', $work->publication_id)
        ->assertJsonPath('data.0.data.publication.title', 'Stock assessment of Atlantic cod')
        ->assertJsonPath('data.0.data.publication.doi', '10.60825/abc-123')
        ->assertJsonPath('data.0.data.publication.published_on', '2025-03-14')
        ->assertJsonPath('data.0.data.publication.journal.data.title', 'DFO Can. Sci. Advis. Sec. Res. Doc.');
});

test('publications that cannot go on ORCID are not listed', function (array $journal, array $publication): void {
    $user = User::factory()->create();
    authorWork($user, $journal, $publication);

    $this->actingAs($user)->getJson('api/user/orcid/works')
        ->assertOk()
        ->assertJsonCount(0, 'data');
})->with([
    'unpublished publication' => [[], ['status' => PublicationStatus::ACCEPTED, 'published_on' => null]],
    'missing DOI' => [[], ['doi' => null]],
    'journal not flagged for ORCID sync' => [['orcid_sync_enabled' => false], []],
    'flagged journal that is no longer a DFO series' => [['publisher' => 'NRC Press'], []],
]);

test('removed authors and deleted publications are not listed', function (Closure $remove): void {
    $user = User::factory()->create();
    $remove(authorWork($user));

    $this->actingAs($user)->getJson('api/user/orcid/works')
        ->assertOk()
        ->assertJsonCount(0, 'data');
})->with([
    'author removed from the publication' => [fn (PublicationAuthor $work) => $work->delete()],
    'publication deleted' => [fn (PublicationAuthor $work) => $work->publication->delete()],
]);

test('the most recently published publications are listed first', function (): void {
    $user = User::factory()->create();
    $older = authorWork($user, publication: ['published_on' => '2023-01-10']);
    $newer = authorWork($user, publication: ['published_on' => '2025-06-01']);

    $this->actingAs($user)->getJson('api/user/orcid/works')
        ->assertOk()
        ->assertJsonPath('data.0.data.id', $newer->id)
        ->assertJsonPath('data.1.data.id', $older->id);
});

test('an author only sees their own publications', function (): void {
    $user = User::factory()->create();
    authorWork(User::factory()->create());

    $this->actingAs($user)->getJson('api/user/orcid/works')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

test('a user without a linked author gets no list', function (): void {
    $user = User::factory()->create();
    $user->author->forceFill(['user_id' => null])->save();
    $user->unsetRelation('author');

    $this->actingAs($user)->getJson('api/user/orcid/works')
        ->assertNotFound();
});

test('an author can add a publication to their ORCID works', function (): void {
    $mockClient = Saloon::fake([
        CreateWork::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/work/4567']),
    ]);
    $user = connectedToOrcid(User::factory()->create());
    $work = authorWork($user);

    $this->actingAs($user)->postJson("api/user/orcid/works/{$work->id}/add")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'on_orcid');

    $work->refresh();
    expect($work->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($work->orcid_sync_at)->not->toBeNull()
        ->and($work->orcid_putcode)->toBe(4567);
    $mockClient->assertSent(fn (CreateWork $request, $response): bool => str_ends_with($response->getPendingRequest()->getUrl(), '/v3.0/0000-0002-1825-0097/work')
        && $request->body()->get('title') === ['title' => ['value' => 'Stock assessment of Atlantic cod']]);

    $activity = Activity::query()->where('subject_type', Publication::class)
        ->where('subject_id', $work->publication_id)
        ->pluck('causer_id', 'description');
    expect($activity->keys()->all())->toContain('publication.author.orcid-wanted', 'publication.author.orcid-created')
        ->and($activity['publication.author.orcid-wanted'])->toBe($user->id);
});

test('a publication added without a working ORCID connection waits for one', function (?string $scope): void {
    $mockClient = Saloon::fake([]);
    $user = User::factory()->create();
    if ($scope !== null) {
        connectedToOrcid($user, $scope);
    }
    $work = authorWork($user);

    $this->actingAs($user)->postJson("api/user/orcid/works/{$work->id}/add")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'pending');

    $mockClient->assertNothingSent();
    expect($work->refresh()->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($work->orcid_putcode)->toBeNull();
})->with([
    'not connected' => [null],
    'connected without write scope' => ['/read-limited'],
]);

test('nobody can add someone else\'s publication to ORCID', function (Closure $makeUser): void {
    $mockClient = Saloon::fake([]);
    $work = authorWork(connectedToOrcid(User::factory()->create()));

    $this->actingAs($makeUser())->postJson("api/user/orcid/works/{$work->id}/add")
        ->assertForbidden();

    $mockClient->assertNothingSent();
    expect($work->refresh()->orcid_sync)->toBeNull();
})->with([
    'another author' => [fn (): User => User::factory()->create()],
    'an editor' => [fn (): User => User::factory()->withRoles([UserRole::EDITOR])->create()],
    'an admin' => [fn (): User => User::factory()->withRoles([UserRole::ADMIN])->create()],
]);

test('a publication that cannot go on ORCID cannot be added', function (): void {
    $mockClient = Saloon::fake([]);
    $user = connectedToOrcid(User::factory()->create());
    $work = authorWork($user, publication: ['doi' => null]);

    $this->actingAs($user)->postJson("api/user/orcid/works/{$work->id}/add")
        ->assertUnprocessable();

    $mockClient->assertNothingSent();
    expect($work->refresh()->orcid_sync)->toBeNull();
});

/**
 * A publication of the user that is on their ORCID record with put-code 4567
 *
 * @param  array<string, mixed>  $journal
 */
function workOnOrcid(User $user, array $journal = []): PublicationAuthor
{
    $work = authorWork($user, $journal);
    $work->forceFill(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()])->save();

    return $work;
}

test('an author can remove a publication from their ORCID works', function (): void {
    $mockClient = Saloon::fake([DeleteWork::class => MockResponse::make('', 204)]);
    $user = connectedToOrcid(User::factory()->create());
    $work = workOnOrcid($user);

    $this->actingAs($user)->postJson("api/user/orcid/works/{$work->id}/remove")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'declined');

    $mockClient->assertSent(fn (DeleteWork $request, $response): bool => str_ends_with($response->getPendingRequest()->getUrl(), '/v3.0/0000-0002-1825-0097/work/4567'));
    $work->refresh();
    expect($work->orcid_sync)->toBe(OrcidSyncIntent::DECLINED)
        ->and($work->orcid_putcode)->toBeNull();

    $activity = Activity::query()->where('subject_type', Publication::class)->where('subject_id', $work->publication_id)->pluck('causer_id', 'description');
    expect($activity->keys()->all())->toContain('publication.author.orcid-declined', 'publication.author.orcid-deleted')
        ->and($activity['publication.author.orcid-declined'])->toBe($user->id);
});

test('a publication removed without a working ORCID connection shows as being removed', function (): void {
    $mockClient = Saloon::fake([]);
    $user = User::factory()->create();
    $work = workOnOrcid($user);

    $this->actingAs($user)->postJson("api/user/orcid/works/{$work->id}/remove")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'removing');

    $mockClient->assertNothingSent();
    expect($work->refresh()->orcid_putcode)->toBe(4567);
});

test('a publication that is no longer eligible can still be removed from ORCID', function (): void {
    $mockClient = Saloon::fake([DeleteWork::class => MockResponse::make('', 204)]);
    $user = connectedToOrcid(User::factory()->create());
    $work = workOnOrcid($user);
    $work->publication->journal->update(['orcid_sync_enabled' => false]);

    $this->actingAs($user)->postJson("api/user/orcid/works/{$work->id}/remove")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'declined');

    $mockClient->assertSent(DeleteWork::class);
    expect($work->refresh()->orcid_putcode)->toBeNull();
});

test('a removed publication can be added to ORCID again', function (): void {
    $mockClient = Saloon::fake([
        DeleteWork::class => MockResponse::make('', 204),
        CreateWork::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/work/7890']),
    ]);
    $user = connectedToOrcid(User::factory()->create());
    $work = workOnOrcid($user);
    $this->actingAs($user)->postJson("api/user/orcid/works/{$work->id}/remove")->assertOk();

    $this->actingAs($user)->postJson("api/user/orcid/works/{$work->id}/add")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'on_orcid');

    $mockClient->assertSent(CreateWork::class);
    expect($work->refresh()->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($work->orcid_putcode)->toBe(7890);
});

test('nobody can remove someone else\'s publication from ORCID', function (Closure $makeUser): void {
    $mockClient = Saloon::fake([]);
    $work = workOnOrcid(connectedToOrcid(User::factory()->create()));

    $this->actingAs($makeUser())->postJson("api/user/orcid/works/{$work->id}/remove")
        ->assertForbidden();

    $mockClient->assertNothingSent();
    expect($work->refresh()->orcid_sync)->toBe(OrcidSyncIntent::WANTED);
})->with([
    'another author' => [fn (): User => User::factory()->create()],
    'an editor' => [fn (): User => User::factory()->withRoles([UserRole::EDITOR])->create()],
    'an admin' => [fn (): User => User::factory()->withRoles([UserRole::ADMIN])->create()],
]);

test('an author can add all their undecided publications to ORCID at once', function (): void {
    $mockClient = Saloon::fake([
        CreateWork::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/work/4567']),
    ]);
    $user = connectedToOrcid(User::factory()->create());
    $undecided = collect([authorWork($user), authorWork($user)]);
    $declined = authorWork($user);
    $declined->forceFill(['orcid_sync' => OrcidSyncIntent::DECLINED])->save();
    $ineligible = authorWork($user, publication: ['doi' => null]);
    $someoneElses = authorWork(User::factory()->create());

    $this->actingAs($user)->postJson('api/user/orcid/works/add-all')
        ->assertOk()
        ->assertJsonCount(3, 'data');

    $mockClient->assertSentCount(2);
    $undecided->each(fn (PublicationAuthor $work) => expect($work->refresh())
        ->orcid_sync->toBe(OrcidSyncIntent::WANTED)
        ->orcid_putcode->toBe(4567));
    expect($declined->refresh()->orcid_sync)->toBe(OrcidSyncIntent::DECLINED)
        ->and($ineligible->refresh()->orcid_sync)->toBeNull()
        ->and($someoneElses->refresh()->orcid_sync)->toBeNull();
    expect(Activity::query()->where('description', 'publication.author.orcid-wanted')->where('causer_id', $user->id)->count())->toBe(2);
});

test('a user without a linked author cannot add all', function (): void {
    $user = User::factory()->create();
    $user->author->forceFill(['user_id' => null])->save();
    $user->unsetRelation('author');

    $this->actingAs($user)->postJson('api/user/orcid/works/add-all')
        ->assertNotFound();
});

test('an author can turn works auto sync on and off, and the change is logged on their author', function (): void {
    /** Keeps updated_at out of the logged changes when the requests cross a second */
    $this->freezeTime();
    $user = User::factory()->create();

    $this->actingAs($user)->putJson('api/user/orcid/works/auto-sync', ['auto_sync' => true])
        ->assertOk()
        ->assertJsonPath('auto_sync', true);
    $this->actingAs($user)->getJson('api/user/orcid/works')->assertJsonPath('meta.auto_sync', true);

    $this->actingAs($user)->putJson('api/user/orcid/works/auto-sync', ['auto_sync' => false])
        ->assertOk()
        ->assertJsonPath('auto_sync', false);
    $this->actingAs($user)->getJson('api/user/orcid/works')->assertJsonPath('meta.auto_sync', false);

    $changes = Activity::query()->forSubject($user->author)->causedBy($user)->where('event', 'updated')->oldest('id')->get()
        ->map(fn (Activity $activity): array => $activity->attribute_changes['attributes']);
    expect($changes->all())->toBe([['orcid_auto_sync_works' => true], ['orcid_auto_sync_works' => false]]);
});

test('works auto sync must be a boolean', function (): void {
    $this->actingAs(User::factory()->create())->putJson('api/user/orcid/works/auto-sync', ['auto_sync' => 'sometimes'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('auto_sync');
});

test('only the author can turn on their works auto sync, not an editor editing their profile', function (): void {
    $user = User::factory()->create();

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/authors/{$user->author->id}", ['orcid_auto_sync_works' => true])
        ->assertOk();

    $this->actingAs($user)->getJson('api/user/orcid/works')->assertJsonPath('meta.auto_sync', false);
});

test('the list tells whether the author can write to ORCID', function (?string $scope, int $expiresInDays, bool $canWrite): void {
    $user = User::factory()->create();
    if ($scope !== null) {
        connectedToOrcid($user, $scope, $expiresInDays);
    }

    $this->actingAs($user)->getJson('api/user/orcid/works')
        ->assertOk()
        ->assertJsonPath('meta.can_write_to_orcid', $canWrite);
})->with([
    'not connected' => [null, 0, false],
    'connected without write scope' => ['/read-limited', 30, false],
    'expired token' => ['/read-limited /activities/update', -1, false],
    'connected with write scope' => ['/read-limited /activities/update', 30, true],
]);
