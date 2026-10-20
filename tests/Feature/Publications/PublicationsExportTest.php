<?php

use App\Models\Author;
use App\Models\FunctionalArea;
use App\Models\Publication;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;

test('a user can export publications to excel', function (): void {
    $user = User::factory()->create();

    $functionalArea = FunctionalArea::factory()->create();

    Publication::factory()
        ->published()
        ->withManuscript(['functional_area_id' => $functionalArea->id])
        ->withAuthors()
        ->create();

    Publication::factory()->create();

    $response = $this->actingAs($user)->get('/api/publications/export?format=excel');

    $response->assertOk();
    $response->assertDownload('publications.xlsx');
});

test('a user can export publications to excel when publication has no manuscript', function (): void {
    $user = User::factory()->create();

    Publication::factory()
        ->published()
        ->withAuthors()
        ->create();

    $response = $this->actingAs($user)->get('/api/publications/export?format=excel');

    $response->assertOk();
    $response->assertDownload('publications.xlsx');
});

test('the excel export lists the sector, region and emeritus status recorded on each author', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->published()->create(['user_id' => $user->id]);
    $author = Author::factory()->withCompleteProfile()->create(['is_emeritus' => true]);
    $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
    ])->assertCreated();

    $response = $this->actingAs($user)->get('/api/publications/export?format=excel')->assertOk();

    $spreadsheet = IOFactory::load($response->baseResponse->getFile()->getPathname());
    $rows = $spreadsheet->getSheet(1)->toArray(); // the authors sheet
    expect(array_slice($rows[0], -3))->toBe(['Sector / Secteur', 'Region / Région', 'Emeritus / Émérite'])
        ->and(array_slice($rows[1], -3))->toBe([
            $author->sector->name_en.' / '.$author->sector->name_fr,
            $author->region->name_en.' / '.$author->region->name_fr,
            'Yes / Oui',
        ]);
});
