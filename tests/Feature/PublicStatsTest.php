<?php

use App\Enums\ManuscriptRecordStatus;
use App\Enums\PublicationStatus;
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
    ])->each(function (Publication $pub) {
        $pub->publicationAuthors()->saveMany(
            PublicationAuthor::factory()->count(2)->make()
        );
    });

    // Create an accepted (non-published) publication — should not count
    Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
    ]);

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

    // External org with ROR — linked to publications via publication_authors
    $externalOrg = Organization::factory()->create([
        'name_en' => 'External University',
        'name_fr' => 'Université externe',
        'ror_identifier' => '03rmrcq20',
    ]);
    Publication::factory()->count(2)->create([
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => now()->subMonth(),
        'doi' => '10.1234/ext-doi',
    ])->each(function (Publication $pub) use ($externalOrg) {
        $pub->publicationAuthors()->save(
            PublicationAuthor::factory()->make(['organization_id' => $externalOrg->id])
        );
    });

    // Org without ROR — should not appear in top_organizations
    $noRorOrg = Organization::factory()->create([
        'name_en' => 'No ROR Org',
        'name_fr' => 'Org sans ROR',
        'ror_identifier' => null,
    ]);
    Publication::factory()->create([
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => now()->subMonths(2),
        'doi' => '10.1234/no-ror-doi',
    ])->publicationAuthors()->save(
        PublicationAuthor::factory()->make(['organization_id' => $noRorOrg->id])
    );

    $response = $this->getJson('/api/stats');

    $response->assertOk();
    $response->assertJsonStructure([
        'publications_count',
        'manuscripts_reviewed_count',
        'authors_count',
        'top_organizations',
        'recent_publications',
    ]);

    expect($response->json('publications_count'))->toBe(6);
    expect($response->json('manuscripts_reviewed_count'))->toBe(3);
    expect($response->json('authors_count'))->toBeGreaterThanOrEqual(3);
    expect($response->json('top_organizations'))->toBeArray();
    expect($response->json('top_organizations.0.ror_identifier'))->toBe('03rmrcq20');
    expect($response->json('top_organizations.0.name_en'))->toBe('External University');
    // Org without ROR should be excluded
    $orgNames = collect($response->json('top_organizations'))->pluck('name_en');
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
