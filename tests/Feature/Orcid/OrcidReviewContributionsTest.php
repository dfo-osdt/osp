<?php

use App\Enums\ManuscriptRecordType;
use App\Enums\PublicationStatus;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use App\Models\Publication;
use App\Models\User;
use VincentAuger\OrcidSdk\Enums\PeerReviewRole;

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

test('the list tells whether the author can write to ORCID', function (?string $scope, int $expiresInDays, bool $canWrite): void {
    $user = User::factory()->create();
    if ($scope !== null) {
        $orcidConnection = Author::factory()->withOrcidConnection($scope)->raw(['orcid_expires_at' => now()->addDays($expiresInDays)]);
        $user->author->forceFill(array_filter($orcidConnection, fn (string $key): bool => str_starts_with($key, 'orcid'), ARRAY_FILTER_USE_KEY))->save();
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
