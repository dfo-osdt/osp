<?php

namespace Tests\Feature\Librarium;

use App\Enums\OrcidSyncStatus;
use App\Enums\Permissions\UserRole;
use App\Filament\Resources\Orcid\Employments\Pages\ListEmployments;
use App\Filament\Resources\Orcid\Employments\Widgets\EmploymentStats;
use App\Models\Author;
use App\Models\AuthorEmployment;
use App\Models\Organization;
use App\Models\User;
use Closure;

use function Livewire\invade;

/**
 * An employment of an author connected to ORCID, not synced yet by default
 *
 * @param  array<string, mixed>  $orcid  The employment's ORCID columns
 */
function auditedEmployment(array $orcid = [], bool $connected = true, ?Organization $organization = null): AuthorEmployment
{
    $employment = AuthorEmployment::factory()->create([
        'author_id' => ($connected ? Author::factory()->withOrcidConnection() : Author::factory())->create()->id,
        'organization_id' => ($organization ?? Organization::factory()->create())->id,
    ]);
    $employment->forceFill($orcid)->saveQuietly();

    return $employment;
}

beforeEach(function (): void {
    $this->admin = User::factory()->withRoles([UserRole::ADMIN])->create();
});

it('an unauthorized user cannot access the list page', function (): void {
    $this->actingAs(User::factory()->create())->get(ListEmployments::getUrl())->assertForbidden();
});

it('an admin can access the list page', function (): void {
    $this->actingAs($this->admin)->get(ListEmployments::getUrl())->assertOk();
});

it('shows where each employment stands with ORCID', function (Closure $makeEmployment, OrcidSyncStatus $status): void {
    $employment = $makeEmployment();

    $this->actingAs($this->admin)->livewire(ListEmployments::class)
        ->assertCanSeeTableRecords([$employment])
        ->assertTableColumnStateSet('orcid_sync_status', $status, $employment);
})->with([
    'author not connected' => [fn (): AuthorEmployment => auditedEmployment(connected: false), OrcidSyncStatus::NOT_CONNECTED],
    'not on ORCID yet' => [fn (): AuthorEmployment => auditedEmployment(), OrcidSyncStatus::PENDING],
    'on ORCID but out of date' => [fn (): AuthorEmployment => auditedEmployment(['orcid_putcode' => 4567, 'orcid_updated_at' => now()->subDay()]), OrcidSyncStatus::PENDING],
    'on ORCID' => [fn (): AuthorEmployment => auditedEmployment(['orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()]), OrcidSyncStatus::ON_ORCID],
    'on ORCID, author since disconnected' => [fn (): AuthorEmployment => auditedEmployment(['orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()], connected: false), OrcidSyncStatus::ON_ORCID],
    'deleted while on ORCID' => [function (): AuthorEmployment {
        $employment = auditedEmployment(['orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()]);
        $employment->delete();

        return $employment;
    }, OrcidSyncStatus::REMOVING],
]);

it('leaves out deleted employments that were never on ORCID', function (): void {
    $deleted = auditedEmployment();
    $deleted->delete();

    $this->actingAs($this->admin)->livewire(ListEmployments::class)
        ->assertCanNotSeeTableRecords([$deleted]);
});

it('filters employments by organization and status', function (): void {
    $organization = Organization::factory()->create();
    $pendingAtOrganization = auditedEmployment(organization: $organization);
    $onOrcidAtOrganization = auditedEmployment(['orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()], organization: $organization);
    $pendingElsewhere = auditedEmployment();

    $this->actingAs($this->admin)->livewire(ListEmployments::class)
        ->filterTable('organization', $organization->id)
        ->assertCanSeeTableRecords([$pendingAtOrganization, $onOrcidAtOrganization])
        ->assertCanNotSeeTableRecords([$pendingElsewhere])
        ->filterTable('orcid_sync_status', OrcidSyncStatus::PENDING->value)
        ->assertCanSeeTableRecords([$pendingAtOrganization])
        ->assertCanNotSeeTableRecords([$onOrcidAtOrganization, $pendingElsewhere]);
});

it('counts employments by status, following the organization filter', function (): void {
    $organization = Organization::factory()->create();
    auditedEmployment(organization: $organization);
    auditedEmployment(connected: false, organization: $organization);
    auditedEmployment(['orcid_putcode' => 4567, 'orcid_updated_at' => now()->addMinute()], organization: $organization);
    auditedEmployment();

    $widget = $this->actingAs($this->admin)->livewire(EmploymentStats::class, [
        'tableFilters' => ['organization' => ['value' => $organization->id]],
    ]);

    $counts = collect(invade($widget->instance())->getStats())
        ->mapWithKeys(fn ($stat): array => [$stat->getLabel() => $stat->getValue()]);

    expect($counts->all())->toBe([
        'Author not connected' => 1,
        'Pending' => 1,
        'On ORCID' => 1,
        'Removing' => 0,
    ]);
});
