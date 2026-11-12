<?php

use App\Enums\ManuscriptRecordType;
use App\Enums\OrcidSyncIntent;
use App\Enums\Permissions\UserRole;
use App\Enums\PublicationStatus;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\User;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Enums\PeerReviewRole;
use VincentAuger\OrcidSdk\Requests\PeerReviews\CreatePeerReview;
use VincentAuger\OrcidSdk\Requests\PeerReviews\DeletePeerReview;

/**
 * A review contribution by the given user's author to a published report in a flagged DFO series.
 *
 * @param  array<string, mixed>  $journal
 * @param  array<string, mixed>  $publication
 */
function reviewContribution(User $user, array $journal = [], array $publication = [], ManuscriptRecordType $manuscriptType = ManuscriptRecordType::SECONDARY): ManuscriptReviewContributor
{
    $manuscript = ManuscriptRecord::factory()->create(['type' => $manuscriptType]);

    Publication::factory()->published()->create([
        'title' => 'Stock assessment of Atlantic cod',
        'doi' => '10.60825/abc-123',
        'manuscript_record_id' => $manuscript->id,
        'journal_id' => Journal::factory()->dfoSeries()->create([
            'title' => 'DFO Can. Sci. Advis. Sec. Res. Doc.',
            'issn' => '1919-5079',
            'orcid_sync_enabled' => true,
            ...$journal,
        ])->id,
        ...$publication,
    ]);

    return ManuscriptReviewContributor::factory()->editor()->create([
        'manuscript_record_id' => $manuscript->id,
        'author_id' => $user->author->id,
    ]);
}

test('an author can list their review contributions that can go on ORCID', function (): void {
    $user = User::factory()->create();
    $contribution = reviewContribution($user);

    $response = $this->actingAs($user)->getJson('api/user/orcid/review-contributions');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.data.id', $contribution->id)
        ->assertJsonPath('data.0.data.role', PeerReviewRole::Editor->value)
        ->assertJsonPath('data.0.data.orcid_status', 'not_on_orcid')
        ->assertJsonPath('data.0.data.publication.title', 'Stock assessment of Atlantic cod')
        ->assertJsonPath('data.0.data.publication.doi', '10.60825/abc-123')
        ->assertJsonPath('data.0.data.publication.journal.data.title', 'DFO Can. Sci. Advis. Sec. Res. Doc.');
});

test('review contributions that cannot go on ORCID are not listed', function (array $journal, array $publication, ManuscriptRecordType $manuscriptType): void {
    $user = User::factory()->create();
    reviewContribution($user, $journal, $publication, $manuscriptType);

    $this->actingAs($user)->getJson('api/user/orcid/review-contributions')
        ->assertOk()
        ->assertJsonCount(0, 'data');
})->with([
    'primary manuscript' => [[], [], ManuscriptRecordType::PRIMARY],
    'unpublished publication' => [[], ['status' => PublicationStatus::ACCEPTED, 'published_on' => null], ManuscriptRecordType::SECONDARY],
    'journal not flagged for ORCID sync' => [['orcid_sync_enabled' => false], [], ManuscriptRecordType::SECONDARY],
    'flagged journal that is no longer a DFO series' => [['publisher' => 'NRC Press'], [], ManuscriptRecordType::SECONDARY],
    'placeholder ISSN' => [['issn' => '1488-XXXE'], [], ManuscriptRecordType::SECONDARY],
    'ISSN with a bad check digit' => [['issn' => '1919-5070'], [], ManuscriptRecordType::SECONDARY],
    'missing ISSN' => [['issn' => null], [], ManuscriptRecordType::SECONDARY],
    'missing DOI' => [[], ['doi' => null], ManuscriptRecordType::SECONDARY],
]);

test('a valid ISSN is accepted despite surrounding spaces or an X check digit', function (string $issn): void {
    $user = User::factory()->create();
    reviewContribution($user, ['issn' => $issn]);

    $this->actingAs($user)->getJson('api/user/orcid/review-contributions')
        ->assertOk()
        ->assertJsonCount(1, 'data');
})->with([' 1919-5079', '1919-5079 ', '0706-652X']);

test('an author only sees their own review contributions', function (): void {
    $user = User::factory()->create();
    reviewContribution(User::factory()->create());

    $this->actingAs($user)->getJson('api/user/orcid/review-contributions')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

test('a user without a linked author gets no list', function (): void {
    $user = User::factory()->create();
    $user->author->forceFill(['user_id' => null])->save();
    $user->unsetRelation('author');

    $this->actingAs($user)->getJson('api/user/orcid/review-contributions')
        ->assertNotFound();
});

test('an author can add a review contribution to ORCID', function (): void {
    $mockClient = Saloon::fake([
        CreatePeerReview::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/peer-review/4567']),
    ]);
    $user = connectedToOrcid(User::factory()->create());
    $contribution = reviewContribution($user);
    $contribution->update(['review_date' => '2025-03-14']);

    $this->actingAs($user)->postJson("api/user/orcid/review-contributions/{$contribution->id}/add")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'on_orcid');

    $contribution->refresh();
    expect($contribution->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($contribution->orcid_putcode)->toBe(4567)
        ->and($contribution->orcid_updated_at)->not->toBeNull();

    $mockClient->assertSent(function (CreatePeerReview $request, $response) use ($contribution): bool {
        $body = $request->body()->all();

        return str_ends_with($response->getPendingRequest()->getUrl(), '/v3.0/0000-0002-1825-0097/peer-review')
            && $body['reviewer-role'] === 'editor'
            && $body['review-type'] === 'review'
            && $body['review-completion-date'] === ['year' => ['value' => '2025'], 'month' => ['value' => '03'], 'day' => ['value' => '14']]
            && $body['review-group-id'] === 'issn:1919-5079'
            && $body['review-identifiers'] === ['external-id' => [[
                'external-id-type' => 'source-work-id',
                'external-id-value' => "{$contribution->manuscriptRecord->ulid}-{$contribution->id}",
                'external-id-relationship' => 'self',
            ]]]
            && ! array_key_exists('review-url', $body)
            && $body['subject-external-identifier'] === [
                'external-id-type' => 'doi',
                'external-id-value' => '10.60825/abc-123',
                'external-id-url' => ['value' => 'https://doi.org/10.60825/abc-123'],
                'external-id-relationship' => 'self',
            ]
            && $body['subject-url'] === ['value' => 'https://doi.org/10.60825/abc-123']
            && $body['subject-type'] === 'report'
            && $body['subject-name']['title'] === ['value' => 'Stock assessment of Atlantic cod']
            && $body['subject-container-name'] === ['value' => 'DFO Can. Sci. Advis. Sec. Res. Doc.']
            && $body['convening-organization']['name'] === Organization::getDefaultOrganization()->name_en
            && $body['convening-organization']['disambiguated-organization']['disambiguated-organization-identifier'] === 'https://ror.org/02qa1x782';
    });

    $activity = Activity::query()->where('subject_type', $contribution->manuscriptRecord->getMorphClass())
        ->where('subject_id', $contribution->manuscript_record_id)
        ->pluck('causer_id', 'description');
    expect($activity->keys()->all())->toContain('manuscript.review-contributor.orcid-wanted', 'manuscript.review-contributor.orcid-created')
        ->and($activity['manuscript.review-contributor.orcid-wanted'])->toBe($user->id);
});

test('a contribution added without a working ORCID connection waits for one', function (?string $scope): void {
    $mockClient = Saloon::fake([]);
    $user = User::factory()->create();
    if ($scope !== null) {
        connectedToOrcid($user, $scope);
    }
    $contribution = reviewContribution($user);

    $this->actingAs($user)->postJson("api/user/orcid/review-contributions/{$contribution->id}/add")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'pending');

    $mockClient->assertNothingSent();
    expect($contribution->refresh()->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($contribution->orcid_putcode)->toBeNull();
})->with([
    'not connected' => [null],
    'connected without write scope' => ['/read-limited'],
]);

test('a contribution added while DFO has no ROR waits for one', function (): void {
    $mockClient = Saloon::fake([]);
    Organization::getDefaultOrganization()->update(['ror_identifier' => null]);
    $user = connectedToOrcid(User::factory()->create());
    $contribution = reviewContribution($user);

    $this->actingAs($user)->postJson("api/user/orcid/review-contributions/{$contribution->id}/add")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'pending');

    $mockClient->assertNothingSent();
});

test('nobody can add someone else\'s contribution to ORCID', function (Closure $makeUser): void {
    Saloon::fake([]);
    $contribution = reviewContribution(connectedToOrcid(User::factory()->create()));

    $this->actingAs($makeUser())->postJson("api/user/orcid/review-contributions/{$contribution->id}/add")
        ->assertForbidden();

    expect($contribution->refresh()->orcid_sync)->toBeNull();
})->with([
    'another author' => [fn (): User => User::factory()->create()],
    'an editor' => [fn (): User => User::factory()->withRoles([UserRole::EDITOR])->create()],
    'an admin' => [fn (): User => User::factory()->withRoles([UserRole::ADMIN])->create()],
]);

test('a contribution that cannot go on ORCID cannot be added', function (): void {
    $user = connectedToOrcid(User::factory()->create());
    $contribution = reviewContribution($user, publication: ['doi' => null]);

    $this->actingAs($user)->postJson("api/user/orcid/review-contributions/{$contribution->id}/add")
        ->assertUnprocessable();

    expect($contribution->refresh()->orcid_sync)->toBeNull();
});

/**
 * A contribution of the user that is on their ORCID record with put-code 4567
 */
function contributionOnOrcid(User $user): ManuscriptReviewContributor
{
    $contribution = reviewContribution($user);
    $contribution->forceFill(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()])->save();

    return $contribution;
}

test('an author can remove a review contribution from ORCID', function (): void {
    $mockClient = Saloon::fake([DeletePeerReview::class => MockResponse::make('', 204)]);
    $user = connectedToOrcid(User::factory()->create());
    $contribution = contributionOnOrcid($user);

    $this->actingAs($user)->postJson("api/user/orcid/review-contributions/{$contribution->id}/remove")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'declined');

    $mockClient->assertSent(fn (DeletePeerReview $request, $response): bool => str_ends_with($response->getPendingRequest()->getUrl(), '/v3.0/0000-0002-1825-0097/peer-review/4567'));
    $contribution->refresh();
    expect($contribution->orcid_sync)->toBe(OrcidSyncIntent::DECLINED)
        ->and($contribution->orcid_putcode)->toBeNull();

    $activity = Activity::query()->where('subject_id', $contribution->manuscript_record_id)->pluck('causer_id', 'description');
    expect($activity->keys()->all())->toContain('manuscript.review-contributor.orcid-declined', 'manuscript.review-contributor.orcid-deleted')
        ->and($activity['manuscript.review-contributor.orcid-declined'])->toBe($user->id);
});

test('a contribution removed without a working ORCID connection shows as being removed', function (): void {
    $mockClient = Saloon::fake([]);
    $user = User::factory()->create();
    $contribution = contributionOnOrcid($user);

    $this->actingAs($user)->postJson("api/user/orcid/review-contributions/{$contribution->id}/remove")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'removing');

    $mockClient->assertNothingSent();
    expect($contribution->refresh()->orcid_putcode)->toBe(4567);
});

test('a contribution that is no longer eligible can still be removed from ORCID', function (): void {
    $mockClient = Saloon::fake([DeletePeerReview::class => MockResponse::make('', 204)]);
    $user = connectedToOrcid(User::factory()->create());
    $contribution = contributionOnOrcid($user);
    $contribution->manuscriptRecord->publication->journal->update(['orcid_sync_enabled' => false]);

    $this->actingAs($user)->postJson("api/user/orcid/review-contributions/{$contribution->id}/remove")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'declined');

    $mockClient->assertSent(DeletePeerReview::class);
    expect($contribution->refresh()->orcid_putcode)->toBeNull();
});

test('removing a contribution that was never on ORCID only declines it', function (): void {
    $mockClient = Saloon::fake([]);
    $user = connectedToOrcid(User::factory()->create());
    $contribution = reviewContribution($user);

    $this->actingAs($user)->postJson("api/user/orcid/review-contributions/{$contribution->id}/remove")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'declined');

    $mockClient->assertNothingSent();
    expect($contribution->refresh()->orcid_sync)->toBe(OrcidSyncIntent::DECLINED);
});

test('a removed contribution can be added to ORCID again', function (): void {
    $mockClient = Saloon::fake([
        DeletePeerReview::class => MockResponse::make('', 204),
        CreatePeerReview::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/peer-review/7890']),
    ]);
    $user = connectedToOrcid(User::factory()->create());
    $contribution = contributionOnOrcid($user);
    $this->actingAs($user)->postJson("api/user/orcid/review-contributions/{$contribution->id}/remove")->assertOk();

    $this->actingAs($user)->postJson("api/user/orcid/review-contributions/{$contribution->id}/add")
        ->assertOk()
        ->assertJsonPath('data.orcid_status', 'on_orcid');

    $mockClient->assertSent(CreatePeerReview::class);
    expect($contribution->refresh()->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($contribution->orcid_putcode)->toBe(7890);
});

test('nobody can remove someone else\'s contribution from ORCID', function (Closure $makeUser): void {
    $mockClient = Saloon::fake([]);
    $contribution = contributionOnOrcid(connectedToOrcid(User::factory()->create()));

    $this->actingAs($makeUser())->postJson("api/user/orcid/review-contributions/{$contribution->id}/remove")
        ->assertForbidden();

    $mockClient->assertNothingSent();
    expect($contribution->refresh()->orcid_sync)->toBe(OrcidSyncIntent::WANTED);
})->with([
    'another author' => [fn (): User => User::factory()->create()],
    'an editor' => [fn (): User => User::factory()->withRoles([UserRole::EDITOR])->create()],
    'an admin' => [fn (): User => User::factory()->withRoles([UserRole::ADMIN])->create()],
]);

test('an author can add all their undecided contributions to ORCID at once', function (): void {
    $mockClient = Saloon::fake([
        CreatePeerReview::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/peer-review/4567']),
    ]);
    $user = connectedToOrcid(User::factory()->create());
    $undecided = collect([reviewContribution($user), reviewContribution($user)]);
    $declined = reviewContribution($user);
    $declined->forceFill(['orcid_sync' => OrcidSyncIntent::DECLINED])->save();
    $ineligible = reviewContribution($user, publication: ['doi' => null]);
    $someoneElses = reviewContribution(User::factory()->create());

    $this->actingAs($user)->postJson('api/user/orcid/review-contributions/add-all')
        ->assertOk()
        ->assertJsonCount(3, 'data');

    $mockClient->assertSentCount(2);
    $undecided->each(fn (ManuscriptReviewContributor $contribution) => expect($contribution->refresh())
        ->orcid_sync->toBe(OrcidSyncIntent::WANTED)
        ->orcid_putcode->toBe(4567));
    expect($declined->refresh()->orcid_sync)->toBe(OrcidSyncIntent::DECLINED)
        ->and($ineligible->refresh()->orcid_sync)->toBeNull()
        ->and($someoneElses->refresh()->orcid_sync)->toBeNull();
    expect(Activity::query()->where('description', 'manuscript.review-contributor.orcid-wanted')->where('causer_id', $user->id)->count())->toBe(2);
});

test('a user without a linked author cannot add all', function (): void {
    $user = User::factory()->create();
    $user->author->forceFill(['user_id' => null])->save();
    $user->unsetRelation('author');

    $this->actingAs($user)->postJson('api/user/orcid/review-contributions/add-all')
        ->assertNotFound();
});

test('the list tells whether the author can write to ORCID', function (?string $scope, int $expiresInDays, bool $canWrite): void {
    $user = User::factory()->create();
    if ($scope !== null) {
        connectedToOrcid($user, $scope, $expiresInDays);
    }

    $this->actingAs($user)->getJson('api/user/orcid/review-contributions')
        ->assertOk()
        ->assertJsonPath('meta.can_write_to_orcid', $canWrite);
})->with([
    'not connected' => [null, 0, false],
    'connected without write scope' => ['/read-limited', 30, false],
    'expired token' => ['/read-limited /activities/update', -1, false],
    'connected with write scope' => ['/read-limited /activities/update', 30, true],
]);
