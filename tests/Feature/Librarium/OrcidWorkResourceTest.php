<?php

namespace Tests\Feature\Librarium;

use App\Enums\OrcidSyncIntent;
use App\Enums\OrcidSyncStatus;
use App\Enums\Permissions\UserRole;
use App\Filament\Resources\Orcid\Works\Pages\ListWorks;
use App\Filament\Resources\Orcid\Works\Widgets\WorkStats;
use App\Models\Author;
use App\Models\Journal;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use Closure;

use function Livewire\invade;

function orcidWorksJournal(bool $enabled = true): Journal
{
    return Journal::factory()->dfoSeries()->create(['orcid_sync_enabled' => $enabled]);
}

/**
 * A publication author, by default published with a DOI in a journal with ORCID
 * sync turned on, by an author with a user account connected to ORCID
 *
 * @param  array<string, mixed>  $orcid  The publication author's ORCID columns
 */
function auditedWork(
    array $orcid = [],
    bool $published = true,
    bool $withUser = true,
    bool $connected = true,
    ?Journal $journal = null,
    ?string $doi = '10.60825/abc-123',
): PublicationAuthor {
    $publication = ($published ? Publication::factory()->published() : Publication::factory())->create([
        'doi' => $doi,
        'journal_id' => ($journal ?? orcidWorksJournal())->id,
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

    $publicationAuthor = PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => $author->id,
    ]);
    $publicationAuthor->forceFill($orcid)->save();

    return $publicationAuthor;
}

beforeEach(function (): void {
    $this->admin = User::factory()->withRoles([UserRole::ADMIN])->create();
});

it('an unauthorized user cannot access the list page', function (): void {
    $this->actingAs(User::factory()->create())->get(ListWorks::getUrl())->assertForbidden();
});

it('an admin can access the list page', function (): void {
    $this->actingAs($this->admin)->get(ListWorks::getUrl())->assertOk();
});

it('shows where each work stands with ORCID', function (Closure $makeWork, OrcidSyncStatus $status): void {
    $work = $makeWork();

    $this->actingAs($this->admin)->livewire(ListWorks::class)
        ->assertCanSeeTableRecords([$work])
        ->assertTableColumnStateSet('orcid_sync_status', $status, $work);
})->with([
    'not published' => [fn (): PublicationAuthor => auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED], published: false), OrcidSyncStatus::NOT_ELIGIBLE],
    'author without a user account' => [fn (): PublicationAuthor => auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED], withUser: false), OrcidSyncStatus::NOT_ELIGIBLE],
    'author not connected' => [fn (): PublicationAuthor => auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED], connected: false), OrcidSyncStatus::NOT_CONNECTED],
    'not decided' => [fn (): PublicationAuthor => auditedWork(), OrcidSyncStatus::NOT_DECIDED],
    'declined' => [fn (): PublicationAuthor => auditedWork(['orcid_sync' => OrcidSyncIntent::DECLINED]), OrcidSyncStatus::DECLINED],
    'wanted, not on ORCID yet' => [fn (): PublicationAuthor => auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED]), OrcidSyncStatus::PENDING],
    'on ORCID but out of date' => [fn (): PublicationAuthor => auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->subDay()]), OrcidSyncStatus::PENDING],
    'on ORCID' => [fn (): PublicationAuthor => auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()]), OrcidSyncStatus::ON_ORCID],
    'on ORCID, author since disconnected' => [fn (): PublicationAuthor => auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()], connected: false), OrcidSyncStatus::ON_ORCID],
    'declined while on ORCID' => [fn (): PublicationAuthor => auditedWork(['orcid_sync' => OrcidSyncIntent::DECLINED, 'orcid_putcode' => 4567]), OrcidSyncStatus::REMOVING],
    'removed while on ORCID' => [function (): PublicationAuthor {
        $work = auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()]);
        $work->delete();

        return $work;
    }, OrcidSyncStatus::REMOVING],
    'publication deleted while on ORCID' => [function (): PublicationAuthor {
        $work = auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()]);
        $work->delete();
        $work->publication->delete();

        return $work;
    }, OrcidSyncStatus::REMOVING],
]);

it('shows why a work is not eligible', function (Closure $makeWork, string $reason): void {
    $work = $makeWork();

    $this->actingAs($this->admin)->livewire(ListWorks::class)
        ->assertTableColumnStateSet('orcid_sync_status', OrcidSyncStatus::NOT_ELIGIBLE, $work)
        ->assertTableColumnHasDescription('orcid_sync_status', $reason, $work);
})->with([
    'not published' => [fn (): PublicationAuthor => auditedWork(published: false), 'Not published'],
    'no DOI' => [fn (): PublicationAuthor => auditedWork(doi: null), 'No DOI'],
    'author without a user account' => [fn (): PublicationAuthor => auditedWork(withUser: false), 'Author has no user account'],
]);

it('leaves out works in journals without ORCID sync and removed authors, unless still on ORCID', function (): void {
    $neverSynced = auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED], journal: orcidWorksJournal(enabled: false));
    $stillOnOrcid = auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567], journal: orcidWorksJournal(enabled: false));
    $removedNeverSynced = auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED]);
    $removedNeverSynced->delete();

    $this->actingAs($this->admin)->livewire(ListWorks::class)
        ->assertCanSeeTableRecords([$stillOnOrcid])
        ->assertCanNotSeeTableRecords([$neverSynced, $removedNeverSynced])
        ->assertTableColumnStateSet('orcid_sync_status', OrcidSyncStatus::REMOVING, $stillOnOrcid);
});

it('filters works by journal and status', function (): void {
    $journal = orcidWorksJournal();
    $pendingInJournal = auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED], journal: $journal);
    $declinedInJournal = auditedWork(['orcid_sync' => OrcidSyncIntent::DECLINED], journal: $journal);
    $pendingElsewhere = auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED]);

    $this->actingAs($this->admin)->livewire(ListWorks::class)
        ->filterTable('journal', $journal->id)
        ->assertCanSeeTableRecords([$pendingInJournal, $declinedInJournal])
        ->assertCanNotSeeTableRecords([$pendingElsewhere])
        ->filterTable('orcid_sync_status', OrcidSyncStatus::PENDING->value)
        ->assertCanSeeTableRecords([$pendingInJournal])
        ->assertCanNotSeeTableRecords([$declinedInJournal, $pendingElsewhere]);
});

it('counts works by status, following the journal filter', function (): void {
    $journal = orcidWorksJournal();
    auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED], journal: $journal);
    auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED], journal: $journal);
    auditedWork(['orcid_sync' => OrcidSyncIntent::DECLINED], journal: $journal);
    auditedWork(['orcid_sync' => OrcidSyncIntent::WANTED]);

    $widget = $this->actingAs($this->admin)->livewire(WorkStats::class, [
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
