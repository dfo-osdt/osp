<?php

use App\Enums\ManuscriptRecordStatus;
use App\Enums\PublicationStatus;
use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;

test('public stats endpoint returns correct structure', function (): void {
    // Create some published publications with DOIs and authors
    Publication::factory()->count(3)->create([
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => now(),
        'doi' => '10.1234/test-doi',
    ])->each(function (Publication $pub): void {
        $pub->publicationAuthors()->saveMany(
            PublicationAuthor::factory()->count(2)->make()
        );
    });

    // Create an accepted (non-published) publication — should not count
    Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
    ]);

    // Create DFO authors with manuscript records
    $defaultOrg = Organization::getDefaultOrganization();
    $dfoAuthor = Author::factory()->create(['organization_id' => $defaultOrg->id]);
    ManuscriptAuthor::factory()->create(['author_id' => $dfoAuthor->id]);

    // Create reviewed manuscripts
    ManuscriptRecord::factory()->count(2)->create([
        'status' => ManuscriptRecordStatus::REVIEWED,
    ]);

    // Create an accepted manuscript
    ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::ACCEPTED,
    ]);

    // Create a draft manuscript — should not count
    ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
    ]);

    // External org with ROR — linked to manuscripts via manuscript_authors
    $externalOrg = Organization::factory()->create([
        'name_en' => 'External University',
        'name_fr' => 'Université externe',
        'ror_identifier' => 'https://ror.org/03rmrcq20',
    ]);
    $externalAuthor = Author::factory()->create(['organization_id' => $externalOrg->id]);
    ManuscriptAuthor::factory()->count(2)->create([
        'author_id' => $externalAuthor->id,
        'organization_id' => $externalOrg->id,
    ]);

    // Also link external org to publications for recent_publications test
    Publication::factory()->count(2)->create([
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => now()->subMonth(),
        'doi' => '10.1234/ext-doi',
    ])->each(function (Publication $pub) use ($externalOrg): void {
        $pub->publicationAuthors()->save(
            PublicationAuthor::factory()->make(['organization_id' => $externalOrg->id])
        );
    });

    // Create authors with verified ORCID
    Author::factory()->count(2)->create(['orcid_verified' => true]);
    // Unverified ORCID — should not count
    Author::factory()->create(['orcid_verified' => false]);

    // Org without ROR linked to manuscripts — should not appear in external_organizations
    $noRorOrg = Organization::factory()->create([
        'name_en' => 'No ROR Org',
        'name_fr' => 'Org sans ROR',
        'ror_identifier' => null,
    ]);
    ManuscriptAuthor::factory()->create(['organization_id' => $noRorOrg->id]);

    $response = $this->getJson('/api/stats');

    $response->assertOk();
    $response->assertJsonStructure([
        'publications_count',
        'manuscripts_reviewed_count',
        'authors_count',
        'external_authors_count',
        'orcid_connected_count',
        'external_organizations',
        'recent_publications',
    ]);

    expect($response->json('publications_count'))->toBe(5);
    expect($response->json('manuscripts_reviewed_count'))->toBe(3);
    expect($response->json('authors_count'))->toBeGreaterThanOrEqual(1);
    expect($response->json('external_authors_count'))->toBeGreaterThanOrEqual(1);
    expect($response->json('orcid_connected_count'))->toBe(2);
    expect($response->json('external_organizations'))->toBeArray();
    // External org with ROR should be present
    $orgNames = collect($response->json('external_organizations'))->pluck('name_en');
    expect($orgNames)->toContain('External University');
    // Org without ROR should be excluded
    expect($orgNames)->not->toContain('No ROR Org');
    expect($response->json('recent_publications'))->toBeArray();
    expect($response->json('recent_publications'))->toHaveCount(5);
    // Authors should be present with name but no email
    $firstPub = $response->json('recent_publications.0');
    expect($firstPub['authors'])->toBeArray();
    expect($firstPub['authors'])->toHaveCount(2);
    expect($firstPub['authors'][0])->toHaveKeys(['first_name', 'last_name', 'orcid', 'orcid_verified']);
    expect($firstPub['authors'][0])->not->toHaveKey('email');
});

test('public stats endpoint does not require authentication', function (): void {
    $response = $this->getJson('/api/stats');

    $response->assertOk();
});
