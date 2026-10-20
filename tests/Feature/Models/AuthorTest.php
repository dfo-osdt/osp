<?php

use App\Enums\Permissions\UserRole;
use App\Models\Author;
use App\Models\Expertise;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\Region;
use App\Models\Sector;
use App\Models\User;

test('a user can get list of authors', function (): void {
    Author::factory()->count(15)->create();

    $user = User::factory()->create();

    // only when user is logged in
    $response = $this->getJson('api/authors')->assertUnauthorized();

    $response = $this->actingAs($user)->getJson('api/authors?limit=5&include=organization')->assertOk();

    expect($response->json('data'))->toHaveCount(5);
    expect($response->json('meta.total'))->toBe(Author::query()->count());
});

test('a user can get a list of authors with a specific expertise', function (): void {
    $expertise = Expertise::factory()->create();
    Author::factory()->count(5)->create()->each(function (Author $author) use ($expertise): void {
        $author->expertises()->attach($expertise);
    });
    Author::factory()->count(5)->create();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson('api/authors?limit=10&filter[expertise_id]='.$expertise->id)->assertOk();

    expect($response->json('data'))->toHaveCount(5);

});

test('a user can create an author', function (): void {
    $user = User::factory()->create();

    $minimumData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'John.Doe@dfo-mpo.gc.ca',
        'orcid' => '',
    ];

    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);

    // email to lowercase in data
    $minimumData['email'] = strtolower($minimumData['email']);

    $response->assertCreated()->assertJson([
        'data' => $minimumData,
    ]);
});

test('a user can create an independent author without an affiliation', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('api/authors', [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane.doe@gmail.com',
        'is_independent' => true,
    ]);

    $independentOrganization = Organization::getIndependentOrganization();

    $response->assertCreated()->assertJson([
        'data' => [
            'organization_id' => $independentOrganization->id,
            'is_independent' => true,
        ],
    ]);
    expect(Author::query()->where('email', 'jane.doe@gmail.com')->value('organization_id'))
        ->toBe($independentOrganization->id);
});

test('a user cannot create an author without an affiliation unless independent', function (array $data): void {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson('api/authors', [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane.doe@gmail.com',
        ...$data,
    ])->assertUnprocessable()->assertJsonValidationErrors('organization_id');
})->with([
    'flag omitted' => [[]],
    'flag false' => [['is_independent' => false]],
]);

test('a user can create an author with an ORCID', function (): void {
    $user = User::factory()->create();

    $invalidFormatOrcid = 'https://orcid.org/0000-0002-X868-2722';
    $badChecksumOrcid = 'https://orcid.org/0000-0002-0868-2725';
    $badUrlPrefix = 'https://orci.org/0000-0002-0868-2726';
    $sandboxUrlPrefix = 'https://sandbox.orcid.org/0000-0002-0868-2726';
    $validOrcid = 'https://orcid.org/0000-0002-0868-2726';

    $minimumData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'john.doe@dfo-mpo.gc.ca',
        'orcid' => $invalidFormatOrcid,
    ];

    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertStatus(422)->assertJsonValidationErrors('orcid');
    expect($response->json('errors.orcid.0'))->toContain('format');

    $minimumData['orcid'] = $badChecksumOrcid;
    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertStatus(422)->assertJsonValidationErrors('orcid');
    expect($response->json('errors.orcid.0'))->toContain('checksum');

    $minimumData['orcid'] = $badUrlPrefix;
    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertStatus(422)->assertJsonValidationErrors('orcid');
    expect($response->json('errors.orcid.0'))->toContain('prefix');

    $minimumData['orcid'] = $sandboxUrlPrefix;
    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertCreated()->assertJson([
        'data' => $minimumData,
    ]);

    $minimumData['orcid'] = $validOrcid;
    $minimumData['email'] = 'john.doe2@test.local';
    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertCreated()->assertJson([
        'data' => $minimumData,
    ]);
});

test('a user cannot create an author if email already exist', function (): void {
    $user = User::factory()->create();

    $author = Author::factory()->create();

    $minimumData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => $author->email,
        'orcid' => '',
    ];

    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);

    $response->assertStatus(422)->assertJsonValidationErrors('email');
});

test('a user cannot create an author if orcid already exists', function (): void {
    $user = User::factory()->create();

    $author = Author::factory()->create([
        'orcid' => 'https://orcid.org/0000-0002-0868-2726',
    ]);

    $minimumData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'john.doe@dfo-mpo.gc.ca',
        'orcid' => $author->orcid,
    ];

    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertStatus(422)->assertJsonValidationErrors('orcid');
});

test('a user can get an author profile', function (): void {
    $user = User::factory()->create();

    $author = Author::factory()->create();

    $response = $this->actingAs($user)->getJson('api/authors/'.$author->id);

    $response->assertOk();
    expect($response->json('data'))->toHaveKey('id', $author->id);
});

test('a user can include the list of publications when querying an author profile', function (): void {
    $user = User::factory()->create();

    $publication = Publication::factory()->withAuthors()->published()->create();
    $author = $publication->publicationAuthors()->first()->author;

    $response = $this->actingAs($user)->getJson('api/authors/'.$author->id.'?include=publications');

    $response->assertOk();
    expect($response->json('data'))->toHaveKey('id', $author->id);
    expect($response->json('data'))->toHaveKey('publications');
    expect($response->json('data.publications'))->toHaveCount(1);
});

test('an editor can update an author profile without an owner', function (): void {
    $user = User::factory()->withRoles([UserRole::EDITOR])->create();

    $author = Author::factory()->create();

    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'kewler.superw@dfo-mpo.gc.ca',
        'orcid' => 'https://orcid.org/0000-0002-0868-2726',
    ];

    $response = $this->actingAs($user)->putJson('api/authors/'.$author->id, $data)->assertOk();

    $response->assertJson([
        'data' => $data,
    ]);
});

test('an editor can update an author profile with an owner', function (): void {
    $editor = User::factory()->withRoles([UserRole::EDITOR])->create();

    // Create an author owned by a different user
    $ownerUser = User::factory()->create();
    $author = $ownerUser->author;

    $newOrganizationId = Organization::factory()->create()->id;

    $data = [
        'first_name' => 'Updated',
        'last_name' => 'Name',
        'organization_id' => $newOrganizationId,
        'email' => 'different@email.com',
        'orcid' => 'https://orcid.org/0000-0002-0868-2726',
    ];

    $response = $this->actingAs($editor)->putJson('api/authors/'.$author->id, $data)->assertOk();

    // first_name, last_name, and email should not be updated since author has a user account
    $data['first_name'] = $author->first_name;
    $data['last_name'] = $author->last_name;
    $data['email'] = $author->email;

    $response->assertJson([
        'data' => $data,
    ]);
});

test('a regular user can update an author profile without an owner', function (): void {
    $user = User::factory()->create(); // Regular user without EDITOR role

    $author = Author::factory()->create(['user_id' => null]); // Author without owner

    $data = [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'jane.smith@dfo-mpo.gc.ca',
        'orcid' => 'https://orcid.org/0000-0002-0868-2726',
    ];

    $response = $this->actingAs($user)->putJson('api/authors/'.$author->id, $data)->assertOk();

    $response->assertJson([
        'data' => $data,
    ]);
});

test('a user can update their own author profile', function (): void {
    $user = User::factory()->create();

    $author = $user->author;

    $data = [
        'first_name' => 'Lala',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'kewler.superw@dfo-mpo.gc.ca',
        'orcid' => 'https://orcid.org/0000-0002-0868-2726',
    ];

    $response = $this->actingAs($user)->putJson('api/authors/'.$author->id, $data)->assertOk();

    // check that first name, last name, and email are not updated here
    $data['first_name'] = $author->first_name;
    $data['last_name'] = $author->last_name;
    $data['email'] = $author->email;

    $response->assertJson([
        'data' => $data,
    ]);
});

test('a user cannot edit an author profile that is owned by another user', function (): void {
    $user = User::factory()->create();

    $author = User::factory()->create()->author;

    $data = [
        'first_name' => 'Lala',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'kewler.superw@dfo-mpo.gc.ca',
        'orcid' => 'https://orcid.org/0000-0002-0868-2726',
    ];

    $response = $this->actingAs($user)->putJson('api/authors/'.$author->id, $data)->assertForbidden();
});

test('an editor can update an author and sync all pivot affiliations', function (): void {
    $user = User::factory()->withRoles([UserRole::EDITOR])->create();

    // Create author with original organization
    $originalOrg = Organization::factory()->create(['name_en' => 'Original University']);
    $author = Author::factory()->create([
        'organization_id' => $originalOrg->id,
    ]);

    // Create manuscripts and publications with the original organization
    $manuscript1 = ManuscriptRecord::factory()->create();
    $manuscript2 = ManuscriptRecord::factory()->create();

    $manuscriptAuthor1 = ManuscriptAuthor::factory()->create([
        'author_id' => $author->id,
        'manuscript_record_id' => $manuscript1->id,
        'organization_id' => $originalOrg->id,
    ]);

    $manuscriptAuthor2 = ManuscriptAuthor::factory()->create([
        'author_id' => $author->id,
        'manuscript_record_id' => $manuscript2->id,
        'organization_id' => $originalOrg->id,
    ]);

    $publication1 = Publication::factory()->create();
    $publication2 = Publication::factory()->create();

    $publicationAuthor1 = PublicationAuthor::factory()->create([
        'author_id' => $author->id,
        'publication_id' => $publication1->id,
        'organization_id' => $originalOrg->id,
    ]);

    $publicationAuthor2 = PublicationAuthor::factory()->create([
        'author_id' => $author->id,
        'publication_id' => $publication2->id,
        'organization_id' => $originalOrg->id,
    ]);

    // Update author with new organization and sync_all_pivots flag
    $newOrg = Organization::factory()->create(['name_en' => 'New University']);

    $data = [
        'organization_id' => $newOrg->id,
        'sync_all_pivots' => true,
    ];

    $response = $this->actingAs($user)->putJson('api/authors/'.$author->id, $data)->assertOk();

    // Verify author was updated
    expect($response->json('data.organization_id'))->toBe($newOrg->id);

    // Verify all manuscript_authors were updated
    $manuscriptAuthor1->refresh();
    $manuscriptAuthor2->refresh();
    expect($manuscriptAuthor1->organization_id)->toBe($newOrg->id);
    expect($manuscriptAuthor2->organization_id)->toBe($newOrg->id);

    // Verify all publication_authors were updated
    $publicationAuthor1->refresh();
    $publicationAuthor2->refresh();
    expect($publicationAuthor1->organization_id)->toBe($newOrg->id);
    expect($publicationAuthor2->organization_id)->toBe($newOrg->id);
});

test('an editor can mark an author as independent and sync all pivot affiliations', function (): void {
    $user = User::factory()->withRoles([UserRole::EDITOR])->create();
    $author = Author::factory()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->create([
        'author_id' => $author->id,
        'organization_id' => $author->organization_id,
    ]);
    $publicationAuthor = PublicationAuthor::factory()->create([
        'author_id' => $author->id,
        'organization_id' => $author->organization_id,
    ]);

    $this->actingAs($user)->putJson('api/authors/'.$author->id, [
        'is_independent' => true,
        'sync_all_pivots' => true,
    ])->assertOk()->assertJsonPath('data.is_independent', true);

    $independentOrganizationId = Organization::getIndependentOrganization()->id;
    expect($author->fresh()->organization_id)->toBe($independentOrganizationId)
        ->and($manuscriptAuthor->fresh()->organization_id)->toBe($independentOrganizationId)
        ->and($publicationAuthor->fresh()->organization_id)->toBe($independentOrganizationId);
});

test('updating author without sync_all_pivots flag does not update pivots', function (): void {
    $user = User::factory()->withRoles([UserRole::EDITOR])->create();

    // Create author with original organization
    $originalOrg = Organization::factory()->create(['name_en' => 'Original University']);
    $author = Author::factory()->create([
        'organization_id' => $originalOrg->id,
    ]);

    // Create a manuscript with the original organization
    $manuscript = ManuscriptRecord::factory()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->create([
        'author_id' => $author->id,
        'manuscript_record_id' => $manuscript->id,
        'organization_id' => $originalOrg->id,
    ]);

    // Update author with new organization but without sync flag
    $newOrg = Organization::factory()->create(['name_en' => 'New University']);

    $data = [
        'organization_id' => $newOrg->id,
        'sync_all_pivots' => false,
    ];

    $response = $this->actingAs($user)->putJson('api/authors/'.$author->id, $data)->assertOk();

    // Verify author was updated
    expect($response->json('data.organization_id'))->toBe($newOrg->id);

    // Verify manuscript_author was NOT updated (preserves historical affiliation)
    $manuscriptAuthor->refresh();
    expect($manuscriptAuthor->organization_id)->toBe($originalOrg->id);
});

test('a regular user cannot sync pivots even if they try', function (): void {
    $regularUser = User::factory()->create(); // Regular user without EDITOR role

    // Create author without owner
    $originalOrg = Organization::factory()->create(['name_en' => 'Original University']);
    $author = Author::factory()->create([
        'user_id' => null,
        'organization_id' => $originalOrg->id,
    ]);

    // Create a manuscript with the original organization
    $manuscript = ManuscriptRecord::factory()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->create([
        'author_id' => $author->id,
        'manuscript_record_id' => $manuscript->id,
        'organization_id' => $originalOrg->id,
    ]);

    // Try to update author with sync_all_pivots flag
    $newOrg = Organization::factory()->create(['name_en' => 'New University']);

    $data = [
        'organization_id' => $newOrg->id,
        'sync_all_pivots' => true,
    ];

    // Should be forbidden because user doesn't have SYNC Author affiliations permission
    $response = $this->actingAs($regularUser)->putJson('api/authors/'.$author->id, $data)->assertForbidden();
});

describe('DFO profile fields', function (): void {

    test('a DFO user must fill their sector and region when updating their own profile', function (): void {
        $user = User::factory()->create();

        $this->actingAs($user)->putJson('api/authors/'.$user->author->id, ['first_name' => 'Jane'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'sector_id' => 'The sector id field is required.',
                'region_id' => 'The region id field is required.',
            ]);
    });

    test('a DFO user can fill their sector, region and emeritus status', function (): void {
        $user = User::factory()->create();
        $sector = Sector::factory()->create();
        $region = Region::query()->first();

        $response = $this->actingAs($user)->putJson('api/authors/'.$user->author->id, [
            'sector_id' => $sector->id,
            'region_id' => $region->id,
            'is_emeritus' => true,
        ])->assertOk();

        $response->assertJsonPath('data.sector.data.name_en', $sector->name_en)
            ->assertJsonPath('data.region.data.id', $region->id)
            ->assertJsonPath('data.is_emeritus', true);
        expect($user->author->refresh())
            ->sector_id->toBe($sector->id)
            ->region_id->toBe($region->id)
            ->is_emeritus->toBeTrue();
    });

    test('a DFO user with a complete profile can update other fields without resending it', function (): void {
        $user = User::factory()->create();
        $user->author->update(['sector_id' => Sector::factory()->create()->id, 'region_id' => Region::query()->value('id')]);

        $this->actingAs($user)->putJson('api/authors/'.$user->author->id, ['orcid' => null])->assertOk();
    });

    test('a DFO user cannot clear their sector', function (): void {
        $user = User::factory()->create();
        $user->author->update(['sector_id' => Sector::factory()->create()->id, 'region_id' => Region::query()->value('id')]);

        $this->actingAs($user)->putJson('api/authors/'.$user->author->id, ['sector_id' => null])
            ->assertJsonValidationErrors(['sector_id' => 'The sector id field is required.']);
    });

    test('someone else editing a DFO author may leave the sector and region empty', function (string $editorKind): void {
        $editor = $editorKind === 'editor'
            ? User::factory()->withRoles([UserRole::EDITOR])->create()
            : User::factory()->create();
        $author = $editorKind === 'editor'
            ? User::factory()->create()->author
            : Author::factory()->internal()->create();

        $this->actingAs($editor)->putJson('api/authors/'.$author->id, ['orcid' => null])->assertOk();
    })->with([
        'an editor on an author with an account' => 'editor',
        'a user on an author without an account' => 'user',
    ]);

    test('the sector must exist', function (): void {
        $user = User::factory()->create();

        $this->actingAs($user)->putJson('api/authors/'.$user->author->id, [
            'sector_id' => 999999,
            'region_id' => Region::query()->value('id'),
        ])->assertJsonValidationErrors(['sector_id' => 'The selected sector id is invalid.']);
    });

    test('profile fields are ignored for a non-DFO author', function (): void {
        $user = User::factory()->create();
        $author = Author::factory()->create();

        $this->actingAs($user)->putJson('api/authors/'.$author->id, [
            'sector_id' => Sector::factory()->create()->id,
            'region_id' => Region::query()->value('id'),
            'is_emeritus' => true,
        ])->assertOk();

        expect($author->refresh())
            ->sector_id->toBeNull()
            ->region_id->toBeNull()
            ->is_emeritus->toBeFalse();
    });

    test('syncing affiliations away from DFO clears the sector, region and emeritus status on manuscripts', function (): void {
        $editor = User::factory()->withRoles([UserRole::EDITOR])->create();
        $author = Author::factory()->withCompleteProfile()->create();
        $manuscript = ManuscriptRecord::factory()->create(['user_id' => $editor->id]);
        $manuscriptAuthorId = $this->actingAs($editor)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
            'author_id' => $author->id,
        ])->assertCreated()->json('data.id');
        $manuscriptAuthor = ManuscriptAuthor::query()->find($manuscriptAuthorId);
        expect($manuscriptAuthor->sector_id)->toBe($author->sector_id);

        $this->actingAs($editor)->putJson('api/authors/'.$author->id, [
            'organization_id' => Organization::factory()->create()->id,
            'sync_all_pivots' => true,
        ])->assertOk();

        expect($manuscriptAuthor->refresh())
            ->sector_id->toBeNull()
            ->region_id->toBeNull()
            ->is_emeritus->toBeNull();
    });

    test('authors can be filtered by sector, region and emeritus status', function (string $filter, Closure $matching): void {
        $user = User::factory()->create();
        $sector = Sector::factory()->create();
        $region = Region::query()->first();
        $expected = Author::factory()->internal()->create($matching($sector, $region));
        Author::factory()->withCompleteProfile()->create();

        $value = match ($filter) {
            'sector_id' => $sector->id,
            'region_id' => $region->id,
            'is_emeritus' => 'true',
        };

        $response = $this->actingAs($user)->getJson("api/authors?filter[{$filter}]={$value}")->assertOk();

        expect($response->json('data.*.data.id'))->toContain($expected->id)
            ->and(collect($response->json('data.*.data'))->every(fn (array $author): bool => match ($filter) {
                'sector_id' => $author['sector_id'] === $sector->id,
                'region_id' => $author['region_id'] === $region->id,
                'is_emeritus' => $author['is_emeritus'] === true,
            }))->toBeTrue();
    })->with([
        'sector' => ['sector_id', fn (Sector $sector, Region $region): array => ['sector_id' => $sector->id]],
        'region' => ['region_id', fn (Sector $sector, Region $region): array => ['region_id' => $region->id]],
        'emeritus' => ['is_emeritus', fn (Sector $sector, Region $region): array => ['is_emeritus' => true]],
    ]);

    test('the authenticated user resource flags a DFO profile that needs an update', function (string $profile, bool $expected): void {
        $user = User::factory()->create();
        match ($profile) {
            'complete' => $user->author->update(['sector_id' => Sector::factory()->create()->id, 'region_id' => Region::query()->value('id')]),
            'external' => $user->author->update(['organization_id' => Organization::factory()->create()->id]),
            default => null,
        };

        $this->actingAs($user)->getJson('api/user')
            ->assertOk()
            ->assertJsonPath('data.profile_updates', $expected ? [['key' => 'dfo-author-profile', 'route' => 'settings.author']] : []);
    })->with([
        'DFO with an empty profile' => ['empty', true],
        'DFO with a complete profile' => ['complete', false],
        'non-DFO' => ['external', false],
    ]);
});

test('authors can be filtered to exclude emeritus authors', function (): void {
    $user = User::factory()->create();
    $emeritus = Author::factory()->internal()->create(['is_emeritus' => true]);

    $response = $this->actingAs($user)->getJson('api/authors?limit=100&filter[is_emeritus]=false')->assertOk();

    expect($response->json('data.*.data.id'))->not->toContain($emeritus->id)->not->toBeEmpty();
});
