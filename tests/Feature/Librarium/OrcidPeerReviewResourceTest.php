<?php

namespace Tests\Feature\Librarium;

use App\Enums\ManuscriptRecordType;
use App\Enums\OrcidSyncIntent;
use App\Enums\OrcidSyncStatus;
use App\Enums\Permissions\UserRole;
use App\Filament\Resources\Orcid\PeerReviews\Pages\ListPeerReviews;
use App\Filament\Resources\Orcid\PeerReviews\Widgets\PeerReviewStats;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use App\Models\Publication;
use App\Models\User;
use Closure;

use function Livewire\invade;

function orcidSyncJournal(bool $enabled = true): Journal
{
    return Journal::factory()->dfoSeries()->create(['issn' => '1919-5079', 'orcid_sync_enabled' => $enabled]);
}

/**
 * A review contribution to a secondary manuscript, by default published with a DOI in a
 * journal with ORCID sync turned on, by an author with a user account connected to ORCID
 *
 * @param  array<string, mixed>  $orcid  The contribution's ORCID columns
 */
function auditedPeerReview(
    array $orcid = [],
    bool $published = true,
    bool $withUser = true,
    bool $connected = true,
    ?Journal $journal = null,
    ?string $doi = '10.60825/abc-123',
    ManuscriptRecordType $type = ManuscriptRecordType::SECONDARY,
): ManuscriptReviewContributor {
    $manuscript = ManuscriptRecord::factory()->create(['type' => $type]);

    ($published ? Publication::factory()->published() : Publication::factory())->create([
        'doi' => $doi,
        'manuscript_record_id' => $manuscript->id,
        'journal_id' => ($journal ?? orcidSyncJournal())->id,
    ]);

    if ($withUser) {
        $user = User::factory()->create();
        $author = $user->author;

        if ($connected) {
            $author->forceFill(Author::factory()->withOrcidConnection()->raw(['email' => $user->email]))->save();
        }
    } else {
        $author = $connected ? Author::factory()->withOrcidConnection()->create() : Author::factory()->create();
    }

    $contributor = ManuscriptReviewContributor::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'author_id' => $author->id,
    ]);
    $contributor->forceFill($orcid)->save();

    return $contributor;
}

beforeEach(function (): void {
    $this->admin = User::factory()->withRoles([UserRole::ADMIN])->create();
});

it('an unauthorized user cannot access the list page', function (): void {
    $this->actingAs(User::factory()->create())->get(ListPeerReviews::getUrl())->assertForbidden();
});

it('an admin can access the list page', function (): void {
    $this->actingAs($this->admin)->get(ListPeerReviews::getUrl())->assertOk();
});

it('shows where each peer review stands with ORCID', function (Closure $makeContribution, OrcidSyncStatus $status): void {
    $contribution = $makeContribution();

    $this->actingAs($this->admin)->livewire(ListPeerReviews::class)
        ->assertCanSeeTableRecords([$contribution])
        ->assertTableColumnStateSet('orcid_sync_status', $status, $contribution);
})->with([
    'not published' => [fn (): ManuscriptReviewContributor => auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED], published: false), OrcidSyncStatus::NOT_ELIGIBLE],
    'author without a user account' => [fn (): ManuscriptReviewContributor => auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED], withUser: false), OrcidSyncStatus::NOT_ELIGIBLE],
    'author not connected' => [fn (): ManuscriptReviewContributor => auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED], connected: false), OrcidSyncStatus::NOT_CONNECTED],
    'not decided' => [fn (): ManuscriptReviewContributor => auditedPeerReview(), OrcidSyncStatus::NOT_DECIDED],
    'declined' => [fn (): ManuscriptReviewContributor => auditedPeerReview(['orcid_sync' => OrcidSyncIntent::DECLINED]), OrcidSyncStatus::DECLINED],
    'wanted, not on ORCID yet' => [fn (): ManuscriptReviewContributor => auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED]), OrcidSyncStatus::PENDING],
    'on ORCID but out of date' => [fn (): ManuscriptReviewContributor => auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->subDay()]), OrcidSyncStatus::PENDING],
    'on ORCID' => [fn (): ManuscriptReviewContributor => auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()]), OrcidSyncStatus::ON_ORCID],
    'on ORCID, author since disconnected' => [fn (): ManuscriptReviewContributor => auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()], connected: false), OrcidSyncStatus::ON_ORCID],
    'declined while on ORCID' => [fn (): ManuscriptReviewContributor => auditedPeerReview(['orcid_sync' => OrcidSyncIntent::DECLINED, 'orcid_putcode' => 4567]), OrcidSyncStatus::REMOVING],
    'deleted while on ORCID' => [function (): ManuscriptReviewContributor {
        $contribution = auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()]);
        $contribution->delete();

        return $contribution;
    }, OrcidSyncStatus::REMOVING],
]);

it('shows why a peer review is not eligible', function (Closure $makeContribution, string $reason): void {
    $contribution = $makeContribution();

    $this->actingAs($this->admin)->livewire(ListPeerReviews::class)
        ->assertTableColumnStateSet('orcid_sync_status', OrcidSyncStatus::NOT_ELIGIBLE, $contribution)
        ->assertTableColumnHasDescription('orcid_sync_status', $reason, $contribution);
})->with([
    'not a DFO publication' => [fn (): ManuscriptReviewContributor => auditedPeerReview(type: ManuscriptRecordType::PRIMARY), 'Not a DFO publication'],
    'not published' => [fn (): ManuscriptReviewContributor => auditedPeerReview(published: false), 'Not published'],
    'no DOI' => [fn (): ManuscriptReviewContributor => auditedPeerReview(doi: null), 'No DOI'],
    'invalid journal ISSN' => [fn (): ManuscriptReviewContributor => auditedPeerReview(journal: Journal::factory()->dfoSeries()->create(['issn' => '1488-XXXE', 'orcid_sync_enabled' => true])), 'Invalid journal ISSN'],
    'author without a user account' => [fn (): ManuscriptReviewContributor => auditedPeerReview(withUser: false), 'Author has no user account'],
]);

it('leaves out peer reviews in journals without ORCID sync, unless still on ORCID', function (): void {
    $neverSynced = auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED], journal: orcidSyncJournal(enabled: false));
    $stillOnOrcid = auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567], journal: orcidSyncJournal(enabled: false));
    $deletedNeverSynced = auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED]);
    $deletedNeverSynced->delete();

    $this->actingAs($this->admin)->livewire(ListPeerReviews::class)
        ->assertCanSeeTableRecords([$stillOnOrcid])
        ->assertCanNotSeeTableRecords([$neverSynced, $deletedNeverSynced])
        ->assertTableColumnStateSet('orcid_sync_status', OrcidSyncStatus::REMOVING, $stillOnOrcid);
});

it('filters peer reviews by journal and status', function (): void {
    $journal = orcidSyncJournal();
    $pendingInJournal = auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED], journal: $journal);
    $declinedInJournal = auditedPeerReview(['orcid_sync' => OrcidSyncIntent::DECLINED], journal: $journal);
    $pendingElsewhere = auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED]);

    $this->actingAs($this->admin)->livewire(ListPeerReviews::class)
        ->filterTable('journal', $journal->id)
        ->assertCanSeeTableRecords([$pendingInJournal, $declinedInJournal])
        ->assertCanNotSeeTableRecords([$pendingElsewhere])
        ->filterTable('orcid_sync_status', OrcidSyncStatus::PENDING->value)
        ->assertCanSeeTableRecords([$pendingInJournal])
        ->assertCanNotSeeTableRecords([$declinedInJournal, $pendingElsewhere]);
});

it('counts peer reviews by status, following the journal filter', function (): void {
    $journal = orcidSyncJournal();
    auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED], journal: $journal);
    auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED], journal: $journal);
    auditedPeerReview(['orcid_sync' => OrcidSyncIntent::DECLINED], journal: $journal);
    auditedPeerReview(['orcid_sync' => OrcidSyncIntent::WANTED]);

    $widget = $this->actingAs($this->admin)->livewire(PeerReviewStats::class, [
        'tableFilters' => ['journal' => ['value' => $journal->id]],
    ]);

    $counts = collect(invade($widget->instance())->getStats())
        ->mapWithKeys(fn ($stat): array => [$stat->getLabel() => $stat->getValue()]);

    expect($counts->all())->toBe([
        'Not eligible' => 0,
        'Author not connected' => 0,
        'Not decided' => 0,
        'Declined' => 1,
        'Pending' => 2,
        'On ORCID' => 0,
        'Removing' => 0,
    ]);
});
