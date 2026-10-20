<?php

namespace Tests\Feature\Librarium;

use App\Filament\Resources\Sectors\Pages\CreateSector;
use App\Filament\Resources\Sectors\Pages\EditSector;
use App\Filament\Resources\Sectors\Pages\ListSectors;
use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\PublicationAuthor;
use App\Models\Sector;
use App\Models\User;

function sectorAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

it('lists the sectors for an admin', function (): void {
    $this->actingAs(sectorAdmin())->livewire(ListSectors::class)
        ->assertCanSeeTableRecords(Sector::all());
});

it('forbids a non-admin from listing the sectors', function (): void {
    $this->actingAs(User::factory()->create())->get(ListSectors::getUrl())->assertForbidden();
});

it('lets an admin create a sector', function (): void {
    $this->actingAs(sectorAdmin())->livewire(CreateSector::class)
        ->fillForm(['name_en' => 'Coast Guard', 'name_fr' => 'Garde côtière'])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('sectors', ['name_en' => 'Coast Guard', 'name_fr' => 'Garde côtière']);
});

it('requires both names to create a sector', function (): void {
    $this->actingAs(sectorAdmin())->livewire(CreateSector::class)
        ->fillForm(['name_en' => '', 'name_fr' => ''])
        ->call('create')
        ->assertHasFormErrors(['name_en' => 'required', 'name_fr' => 'required']);
});

it('lets an admin rename a sector', function (): void {
    $sector = Sector::factory()->create();

    $this->actingAs(sectorAdmin())->livewire(EditSector::class, ['record' => $sector->getRouteKey()])
        ->fillForm(['name_en' => 'Renamed', 'name_fr' => 'Renommé'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($sector->refresh()->name_en)->toBe('Renamed');
});

it('lets an admin delete a sector that is not in use', function (): void {
    $sector = Sector::factory()->create();

    $this->actingAs(sectorAdmin())->livewire(EditSector::class, ['record' => $sector->getRouteKey()])
        ->callAction('delete');

    $this->assertModelMissing($sector);
});

it('hides the delete action for a sector in use', function (string $table): void {
    $sector = Sector::factory()->create();
    $author = Author::factory()->internal()->create(['sector_id' => $table === 'authors' ? $sector->id : null]);

    $snapshot = ['organization_id' => $author->organization_id, 'sector_id' => $sector->id];

    match ($table) {
        'manuscript_authors' => ManuscriptAuthor::factory()->for($author)->create($snapshot),
        'publication_authors' => PublicationAuthor::factory()->for($author)->create($snapshot),
        default => null,
    };

    $this->actingAs(sectorAdmin())->livewire(EditSector::class, ['record' => $sector->getRouteKey()])
        ->assertActionHidden('delete');
})->with([
    'referenced by an author' => 'authors',
    'referenced by a manuscript author' => 'manuscript_authors',
    'referenced by a publication author' => 'publication_authors',
]);
