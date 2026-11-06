<?php

use App\Enums\ManuscriptRecordType;
use App\Enums\OrcidSyncIntent;
use App\Enums\Permissions\UserRole;
use App\Enums\PublicationStatus;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use App\Models\Publication;
use App\Models\User;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Requests\PeerReviews\CreatePeerReview;
use VincentAuger\OrcidSdk\Requests\PeerReviews\DeletePeerReview;

/**
 * An accepted report in a flagged DFO series, on a secondary manuscript
 *
 * @param  array<string, mixed>  $journal
 */
function acceptedReport(array $journal = []): Publication
{
    return Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'accepted_on' => now()->subMonth(),
        'doi' => null,
        'manuscript_record_id' => ManuscriptRecord::factory()->create(['type' => ManuscriptRecordType::SECONDARY])->id,
        'journal_id' => Journal::factory()->dfoSeries()->create(['issn' => '1919-5079', 'orcid_sync_enabled' => true, ...$journal])->id,
    ]);
}

/**
 * A review contribution to the publication by an author connected to ORCID
 *
 * @param  array<string, mixed>  $author
 */
function contributionTo(Publication $publication, array $author = ['orcid_auto_sync_reviews' => true], ?OrcidSyncIntent $intent = null): ManuscriptReviewContributor
{
    $contributor = ManuscriptReviewContributor::factory()->create([
        'manuscript_record_id' => $publication->manuscript_record_id,
        'author_id' => Author::factory()->withOrcidConnection()->create($author)->id,
    ]);
    $contributor->forceFill(['orcid_sync' => $intent])->save();

    return $contributor;
}

/**
 * Publish the publication as a chief editor, who publishes DFO reports
 *
 * @param  array<string, mixed>  $attributes
 */
function publishReport(Publication $publication, array $attributes = ['doi' => 'https://doi.org/10.60825/abc-123']): User
{
    $chiefEditor = User::factory()->withRoles([UserRole::CHIEF_EDITOR])->create();

    test()->actingAs($chiefEditor)
        ->putJson("api/publications/{$publication->id}", [
            'status' => PublicationStatus::PUBLISHED->value,
            'published_on' => now()->toDateString(),
            ...$attributes,
        ])
        ->assertOk();

    return $chiefEditor;
}

function createdOnOrcid(): MockResponse
{
    return MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/peer-review/4567']);
}

test('publishing adds the contributions of authors with auto sync on to ORCID, logged on the manuscript', function (): void {
    $mockClient = Saloon::fake([CreatePeerReview::class => createdOnOrcid()]);
    $publication = acceptedReport();
    $contributor = contributionTo($publication);

    $chiefEditor = publishReport($publication);

    $mockClient->assertSentCount(1, CreatePeerReview::class);
    $contributor->refresh();
    expect($contributor->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($contributor->orcid_putcode)->toBe(4567);

    $activity = Activity::query()->forSubject($publication->manuscriptRecord)->where('description', 'manuscript.review-contributor.orcid-wanted')->sole();
    expect($activity->causer_id)->toBe($chiefEditor->id)
        ->and($activity->properties->all())->toBe(['author_id' => $contributor->author_id, 'orcid_sync' => 'wanted', 'auto_sync' => true]);
});

test('publishing leaves alone contributions that are not to be added automatically', function (Closure $arrange, array $publishedWith): void {
    $mockClient = Saloon::fake([]);
    $publication = acceptedReport();
    [$contributor, $intent] = $arrange($publication);

    publishReport($publication, $publishedWith);

    $mockClient->assertNothingSent();
    expect($contributor->refresh()->orcid_sync)->toBe($intent);
})->with([
    'auto sync off' => [fn (Publication $publication): array => [contributionTo($publication, ['orcid_auto_sync_reviews' => false]), null], ['doi' => 'https://doi.org/10.60825/abc-123']],
    'declined' => [fn (Publication $publication): array => [contributionTo($publication, intent: OrcidSyncIntent::DECLINED), OrcidSyncIntent::DECLINED], ['doi' => 'https://doi.org/10.60825/abc-123']],
    'no DOI' => [fn (Publication $publication): array => [contributionTo($publication), null], []],
    'journal not flagged' => [function (Publication $publication): array {
        $publication->journal->update(['orcid_sync_enabled' => false]);

        return [contributionTo($publication), null];
    }, ['doi' => 'https://doi.org/10.60825/abc-123']],
]);

test('contributions becoming eligible after publishing are not added automatically', function (): void {
    $mockClient = Saloon::fake([]);
    $publication = acceptedReport();
    $contributor = contributionTo($publication);
    publishReport($publication, []);

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/publications/{$publication->id}", ['published_on' => now()->toDateString(), 'doi' => 'https://doi.org/10.60825/abc-123'])
        ->assertOk();

    $mockClient->assertNothingSent();
    expect($contributor->refresh()->orcid_sync)->toBeNull();
});

test('turning auto sync on does not add contributions already published', function (): void {
    $mockClient = Saloon::fake([]);
    $publication = acceptedReport();
    $contributor = contributionTo($publication, ['orcid_auto_sync_reviews' => false]);
    publishReport($publication);
    $user = User::factory()->create();
    $contributor->author->update(['user_id' => $user->id]);

    $this->actingAs($user)->putJson('api/user/orcid/review-contributions/auto-sync', ['auto_sync' => true])->assertOk();

    $mockClient->assertNothingSent();
    expect($contributor->refresh()->orcid_sync)->toBeNull();
});

test('turning auto sync off leaves contributions on ORCID', function (): void {
    $mockClient = Saloon::fake([CreatePeerReview::class => createdOnOrcid()]);
    $publication = acceptedReport();
    $contributor = contributionTo($publication);
    publishReport($publication);
    $user = User::factory()->create();
    $contributor->author->update(['user_id' => $user->id]);

    $this->actingAs($user)->putJson('api/user/orcid/review-contributions/auto-sync', ['auto_sync' => false])->assertOk();

    $mockClient->assertNotSent(DeletePeerReview::class);
    $contributor->refresh();
    expect($contributor->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($contributor->orcid_putcode)->toBe(4567);
});

test('auto sync is off by default', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->getJson('api/user/orcid/review-contributions')
        ->assertOk()
        ->assertJsonPath('meta.auto_sync', false);
});

test('an author can turn auto sync on and off, and the change is logged on their author', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->putJson('api/user/orcid/review-contributions/auto-sync', ['auto_sync' => true])
        ->assertOk()
        ->assertJsonPath('auto_sync', true);
    $this->actingAs($user)->getJson('api/user/orcid/review-contributions')->assertJsonPath('meta.auto_sync', true);

    $this->actingAs($user)->putJson('api/user/orcid/review-contributions/auto-sync', ['auto_sync' => false])
        ->assertOk()
        ->assertJsonPath('auto_sync', false);
    $this->actingAs($user)->getJson('api/user/orcid/review-contributions')->assertJsonPath('meta.auto_sync', false);

    $changes = Activity::query()->forSubject($user->author)->causedBy($user)->where('event', 'updated')->oldest('id')->get()
        ->map(fn (Activity $activity): array => $activity->attribute_changes['attributes']);
    expect($changes->all())->toBe([['orcid_auto_sync_reviews' => true], ['orcid_auto_sync_reviews' => false]]);
});

test('auto sync must be a boolean', function (): void {
    $this->actingAs(User::factory()->create())->putJson('api/user/orcid/review-contributions/auto-sync', ['auto_sync' => 'sometimes'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('auto_sync');
});

test('only the author can turn on their auto sync, not an editor editing their profile', function (): void {
    $user = User::factory()->create();

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/authors/{$user->author->id}", ['orcid_auto_sync_reviews' => true])
        ->assertOk();

    $this->actingAs($user)->getJson('api/user/orcid/review-contributions')->assertJsonPath('meta.auto_sync', false);
});
