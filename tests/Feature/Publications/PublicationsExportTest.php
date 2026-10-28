<?php

use App\Enums\PublicationStatus;
use App\Models\Author;
use App\Models\FunctionalArea;
use App\Models\Journal;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;
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

test('the excel export writes yes and no values in both languages', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->published()->create(['user_id' => $user->id, 'is_open_access' => true]);
    $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => Author::factory()->create()->id,
        'is_corresponding_author' => false,
    ])->assertCreated();

    $response = $this->actingAs($user)->get('/api/publications/export?format=excel')->assertOk();

    $spreadsheet = IOFactory::load($response->baseResponse->getFile()->getPathname());
    $firstRowValue = function (int $sheet, string $heading) use ($spreadsheet): mixed {
        $rows = $spreadsheet->getSheet($sheet)->toArray();

        return $rows[1][array_search($heading, $rows[0], true)];
    };
    expect($firstRowValue(0, 'Open Access / Libre accès'))->toBe('Yes / Oui')
        ->and($firstRowValue(1, 'Corresponding Author / Auteur correspondant'))->toBe('No / Non');
});

test('the excel export uses the organization name for group authors', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->published()->create();
    $organization = Organization::factory()->create([
        'name_en' => 'Canadian Salmon Research Group',
    ]);
    $contact = Author::factory()->create([
        'first_name' => 'Contact',
        'last_name' => 'Person',
    ]);
    PublicationAuthor::factory()
        ->for($publication)
        ->for($contact)
        ->groupAuthor()
        ->create(['organization_id' => $organization->id]);

    $response = $this->actingAs($user)->get('/api/publications/export?format=excel')->assertOk();

    $spreadsheet = IOFactory::load($response->baseResponse->getFile()->getPathname());
    $rows = $spreadsheet->getSheet(1)->toArray();
    $authorColumn = array_search('Author / Auteur', $rows[0], true);
    expect($rows[1][$authorColumn])->toBe('Canadian Salmon Research Group');
});

test('the ris export uses the organization name for group authors', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->published()->create();
    $organization = Organization::factory()->create([
        'name_en' => 'Canadian Salmon Research Group',
    ]);
    $contact = Author::factory()->create([
        'first_name' => 'Contact',
        'last_name' => 'Person',
    ]);
    PublicationAuthor::factory()
        ->for($publication)
        ->for($contact)
        ->groupAuthor()
        ->create(['organization_id' => $organization->id]);

    $response = $this->actingAs($user)->get('/api/publications/export?format=ris');

    expect($response->streamedContent())
        ->toContain('AU  - Canadian Salmon Research Group')
        ->not->toContain('AU  - Person, Contact');
});

test('a user can export publications to bibtex', function (): void {
    $user = User::factory()->create();
    $journal = Journal::factory()->create([
        'title' => 'Canadian Journal of Fisheries and Aquatic Sciences',
        'publisher' => 'Canadian Science Publishing',
        'issn' => '0706-652X',
    ]);
    $publication = Publication::factory()->published()->create([
        'journal_id' => $journal->id,
        'title' => 'Growth of juvenile salmon',
        'published_on' => '2024-03-15',
        'doi' => 'https://doi.org/10.1139/cjfas-2023_0001',
        'isbn' => '978-0-660-12345-6',
    ]);
    PublicationAuthor::factory()->for($publication)->for(Author::factory()->state(['first_name' => 'Marie', 'last_name' => 'Côté']))->create();
    PublicationAuthor::factory()->for($publication)->for(Author::factory()->state(['first_name' => 'Jean', 'last_name' => 'Smith']))->create();

    $response = $this->actingAs($user)->get('/api/publications/export?format=bibtex');

    $response->assertOk();
    $response->assertDownload('publications.bib');
    $response->assertHeader('Content-Type', 'application/x-bibtex');
    expect($response->streamedContent())->toBe(<<<BIB
        @article{Cote2024_{$publication->id},
          author = {Côté, Marie and Smith, Jean},
          title = {Growth of juvenile salmon},
          journal = {Canadian Journal of Fisheries and Aquatic Sciences},
          publisher = {Canadian Science Publishing},
          year = {2024},
          month = mar,
          issn = {0706-652X},
          isbn = {978-0-660-12345-6},
          doi = {10.1139/cjfas-2023_0001}
        }


        BIB);
});

test('the bibtex export writes dfo series as technical reports', function (): void {
    $user = User::factory()->create();
    $journal = Journal::factory()->dfoSeries()->create([
        'title' => 'Canadian Technical Report of Fisheries and Aquatic Sciences',
        'issn' => '1488-5379',
    ]);
    $publication = Publication::factory()->published()->create([
        'journal_id' => $journal->id,
        'title' => 'Survey of coastal habitats',
        'published_on' => '2025-11-02',
        'issue_number' => '3456',
        'doi' => null,
    ]);
    PublicationAuthor::factory()->for($publication)->for(Author::factory()->state(['first_name' => 'Ann', 'last_name' => 'Lee']))->create();

    $response = $this->actingAs($user)->get('/api/publications/export?format=bibtex');

    expect($response->streamedContent())->toBe(<<<BIB
        @techreport{Lee2025_{$publication->id},
          author = {Lee, Ann},
          title = {Survey of coastal habitats},
          type = {Canadian Technical Report of Fisheries and Aquatic Sciences},
          institution = {Fisheries and Oceans Canada - Pêches et Océans Canada},
          number = {3456},
          year = {2025},
          month = nov,
          issn = {1488-5379}
        }


        BIB);
});

test('the bibtex export uses the organization name for group authors', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->published()->create([
        'title' => 'Salmon conservation update',
        'published_on' => '2024-06-10',
    ]);
    $organization = Organization::factory()->create([
        'name_en' => 'Canadian Salmon Research Group',
    ]);
    $contact = Author::factory()->create([
        'first_name' => 'Contact',
        'last_name' => 'Person',
    ]);
    PublicationAuthor::factory()
        ->for($publication)
        ->for($contact)
        ->groupAuthor()
        ->create(['organization_id' => $organization->id]);

    $response = $this->actingAs($user)->get('/api/publications/export?format=bibtex');

    expect($response->streamedContent())
        ->toContain("@article{CanadianSalmonResearchGroup2024_{$publication->id},")
        ->toContain('  author = {{Canadian Salmon Research Group}},')
        ->not->toContain('Person, Contact');
});

test('the bibtex export escapes special characters and protects acronyms in titles', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->published()->create([
        'title' => 'R&D on eDNA_sampling: 50% of $costs #1 {DFO} ~ x^2 \\ end',
    ]);
    PublicationAuthor::factory()->for($publication)->for(Author::factory()->state(['first_name' => 'Al', 'last_name' => 'Smith & Sons']))->create();

    $response = $this->actingAs($user)->get('/api/publications/export?format=bibtex');

    expect($response->streamedContent())
        ->toContain('  author = {Smith \& Sons, Al},')
        ->toContain('  title = {{R\&D} on {eDNA\_sampling:} 50\% of \$costs \#1 {\{DFO\}} \textasciitilde{} x\textasciicircum{}2 \textbackslash{} end},');
});

test('the bibtex export omits missing fields and falls back to a generic citation key', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => null,
    ]);

    $response = $this->actingAs($user)->get('/api/publications/export?format=bibtex');

    expect($response->streamedContent())
        ->toContain("@article{publication_{$publication->id},")
        ->not->toContain('author =')
        ->not->toContain('year =')
        ->not->toContain('month =');
});

test('the bibtex export only includes filtered publications the user can see', function (): void {
    $user = User::factory()->create();
    $visible = Publication::factory()->published()->create(['title' => 'Salmon migration']);
    $filteredOut = Publication::factory()->published()->create(['title' => 'Cod stocks']);
    $unpublished = Publication::factory()->create(['title' => 'Salmon genetics']);

    $response = $this->actingAs($user)->get('/api/publications/export?format=bibtex&filter[title]=Salmon');

    expect($response->streamedContent())
        ->toContain("_{$visible->id},")
        ->not->toContain("_{$filteredOut->id},")
        ->not->toContain("_{$unpublished->id},");
});
