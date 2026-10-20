<?php

use App\Exports\PublicationAuthorsSheet;
use App\Models\Author;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Queries\PublicationListQuery;
use Illuminate\Http\Request;

test('the authors sheet exports the sector, region and emeritus snapshot', function (): void {
    $author = Author::factory()->withCompleteProfile()->create(['is_emeritus' => true]);
    $publication = Publication::factory()->create();
    PublicationAuthor::factory()->for($publication)->for($author)->create([
        'organization_id' => Organization::getDefaultOrganization()->id,
    ]);
    $sheet = new PublicationAuthorsSheet(new PublicationListQuery(Publication::query(), new Request));

    $row = $sheet->map($publication->load('publicationAuthors.author', 'publicationAuthors.organization'))[0];

    expect(array_slice($sheet->headings(), -3))->toBe(['Sector / Secteur', 'Region / Région', 'Emeritus / Émérite'])
        ->and(array_slice($row, -3))->toBe([
            $author->sector->name_en.' / '.$author->sector->name_fr,
            $author->region->name_en.' / '.$author->region->name_fr,
            'Yes',
        ]);
});

test('the authors sheet leaves the snapshot columns empty for non-DFO authors', function (): void {
    $publication = Publication::factory()->create();
    PublicationAuthor::factory()->for($publication)->create();
    $sheet = new PublicationAuthorsSheet(new PublicationListQuery(Publication::query(), new Request));

    $row = $sheet->map($publication->load('publicationAuthors.author', 'publicationAuthors.organization'))[0];

    expect(array_slice($row, -3))->toBe([null, null, null]);
});
