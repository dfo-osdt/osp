<?php

use App\Enums\ManuscriptRecordStatus;
use App\Enums\Permissions\UserRole;
use App\Models\ManuscriptRecord;
use App\Models\Region;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Seed regions with slugs if they don't exist
    if (Region::count() === 0) {
        Region::factory()->create(['id' => 1, 'slug' => 'nfl', 'name_en' => 'Newfoundland and Labrador']);
        Region::factory()->create(['id' => 2, 'slug' => 'mar', 'name_en' => 'Maritimes']);
        Region::factory()->create(['id' => 3, 'slug' => 'glf', 'name_en' => 'Gulf']);
        Region::factory()->create(['id' => 4, 'slug' => 'que', 'name_en' => 'Quebec']);
        Region::factory()->create(['id' => 5, 'slug' => 'onp', 'name_en' => 'Ontario and Prairie']);
        Region::factory()->create(['id' => 6, 'slug' => 'arc', 'name_en' => 'Arctic']);
        Region::factory()->create(['id' => 7, 'slug' => 'pac', 'name_en' => 'Pacific']);
        Region::factory()->create(['id' => 8, 'slug' => 'ncr', 'name_en' => 'National Capital Region']);
    }

    // Create roles if they don't exist
    foreach (UserRole::cases() as $role) {
        if (! Role::where('name', $role->value)->exists()) {
            Role::create([
                'name' => $role->value,
                'permissions' => $role->permissionValues(),
            ]);
        }
    }
});

test('regional editor can view draft manuscript in their region', function () {
    $nflEditor = User::factory()->create();
    $nflEditor->assignRole(UserRole::NFL_EDITOR->value);

    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 1, // NFL region
    ]);

    $response = $this->actingAs($nflEditor)
        ->getJson("/api/manuscript-records/{$manuscript->id}")
        ->assertOk();

    $response->assertOk();
    expect($response->json('data.id'))->toBe($manuscript->id);
});

test('regional editor can view in_review manuscript in their region', function () {
    $marEditor = User::factory()->create();
    $marEditor->assignRole(UserRole::MAR_EDITOR->value);

    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'region_id' => 2, // MAR region
    ]);

    $response = $this->actingAs($marEditor)
        ->getJson("/api/manuscript-records/{$manuscript->id}")
        ->assertOk();

    $response->assertOk();
    expect($response->json('data.id'))->toBe($manuscript->id);
});

test('regional editor cannot view manuscript outside their region', function () {
    $nflEditor = User::factory()->create();
    $nflEditor->assignRole(UserRole::NFL_EDITOR->value);

    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 2, // MAR region (not NFL)
    ]);

    $this->actingAs($nflEditor)
        ->getJson("/api/manuscript-records/{$manuscript->id}")
        ->assertForbidden();
});

test('regional editor can edit draft manuscript in their region', function () {
    $queEditor = User::factory()->create();
    $queEditor->assignRole(UserRole::QUE_EDITOR->value);

    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 4, // QUE region
        'title' => 'Original Title',
    ]);

    $response = $this->actingAs($queEditor)
        ->putJson("/api/manuscript-records/{$manuscript->id}", [
            'title' => 'Updated by QUE Editor',
        ])
        ->assertOk();

    expect($response->json('data.title'))->toBe('Updated by QUE Editor');
    expect($response->json('can.update'))->toBe(true);

    // Verify in database
    expect($manuscript->fresh()->title)->toBe('Updated by QUE Editor');
});

test('regional editor can edit in_review manuscript in their region', function () {
    $onpEditor = User::factory()->create();
    $onpEditor->assignRole(UserRole::ONP_EDITOR->value);

    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'region_id' => 5, // ONP region
        'title' => 'Original Title',
    ]);

    $response = $this->actingAs($onpEditor)
        ->putJson("/api/manuscript-records/{$manuscript->id}", [
            'title' => 'Updated by ONP Editor',
        ])
        ->assertOk();

    expect($response->json('data.title'))->toBe('Updated by ONP Editor');
    expect($response->json('can.update'))->toBe(true);
});

test('regional editor cannot edit manuscript outside their region', function () {
    $arcEditor = User::factory()->create();
    $arcEditor->assignRole(UserRole::ARC_EDITOR->value);

    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 7, // PAC region (not ARC)
        'title' => 'Original Title',
    ]);

    $this->actingAs($arcEditor)
        ->putJson("/api/manuscript-records/{$manuscript->id}", [
            'title' => 'Should Not Update',
        ])
        ->assertForbidden();

    // Verify title was not changed
    expect($manuscript->fresh()->title)->toBe('Original Title');
});

test('regional editor cannot edit non-editable statuses', function () {
    $pacEditor = User::factory()->create();
    $pacEditor->assignRole(UserRole::PAC_EDITOR->value);

    $nonEditableStatuses = [
        ManuscriptRecordStatus::REVIEWED,
        ManuscriptRecordStatus::SUBMITTED,
        ManuscriptRecordStatus::ACCEPTED,
        ManuscriptRecordStatus::WITHDRAWN,
    ];

    foreach ($nonEditableStatuses as $status) {
        $manuscript = ManuscriptRecord::factory()->create([
            'status' => $status,
            'region_id' => 7, // PAC region
            'title' => 'Original Title',
        ]);

        $this->actingAs($pacEditor)
            ->putJson("/api/manuscript-records/{$manuscript->id}", [
                'title' => 'Should Not Update',
            ])
            ->assertForbidden();

        expect($manuscript->fresh()->title)->toBe('Original Title');
    }
});

test('global editor can view non-draft manuscripts from any region', function () {
    $globalEditor = User::factory()->create();
    $globalEditor->assignRole(UserRole::EDITOR->value);

    $manuscripts = [
        ManuscriptRecord::factory()->create([
            'status' => ManuscriptRecordStatus::IN_REVIEW,
            'region_id' => 1, // NFL
        ]),
        ManuscriptRecord::factory()->create([
            'status' => ManuscriptRecordStatus::REVIEWED,
            'region_id' => 2, // MAR
        ]),
        ManuscriptRecord::factory()->create([
            'status' => ManuscriptRecordStatus::SUBMITTED,
            'region_id' => 8, // NCR
        ]),
    ];

    foreach ($manuscripts as $manuscript) {
        $response = $this->actingAs($globalEditor)
            ->getJson("/api/manuscript-records/{$manuscript->id}")
            ->assertOk();

        $response->assertOk();
        expect($response->json('data.id'))->toBe($manuscript->id);

    }
});

test('global editor cannot view draft manuscripts unless they are regional editor for that region', function () {
    $globalEditor = User::factory()->create();
    $globalEditor->assignRole(UserRole::EDITOR->value);

    $draftManuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 1, // NFL region
    ]);

    $this->actingAs($globalEditor)
        ->getJson("/api/manuscript-records/{$draftManuscript->id}")
        ->assertForbidden();
});

test('user with both global and regional permissions can access all manuscripts including drafts in their region', function () {
    $hybridEditor = User::factory()->create();
    $hybridEditor->assignRole([UserRole::EDITOR->value, UserRole::NCR_EDITOR->value]);

    // Can view draft in their region
    $draftInRegion = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 8, // NCR region
    ]);

    $response = $this->actingAs($hybridEditor)
        ->getJson("/api/manuscript-records/{$draftInRegion->id}")
        ->assertOk();

    $response->assertOk();

    // Can view non-draft outside their region
    $nonDraftOutsideRegion = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::REVIEWED,
        'region_id' => 1, // NFL region
    ]);

    $response = $this->actingAs($hybridEditor)
        ->getJson("/api/manuscript-records/{$nonDraftOutsideRegion->id}")
        ->assertOk();

    $response->assertOk();

    // Cannot view draft outside their region
    $draftOutsideRegion = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 1, // NFL region (not NCR)
    ]);

    $this->actingAs($hybridEditor)
        ->getJson("/api/manuscript-records/{$draftOutsideRegion->id}")
        ->assertForbidden();
});

test('editor with multiple regional roles can access manuscripts from all assigned regions', function () {
    $multiRegionEditor = User::factory()->create();
    $multiRegionEditor->assignRole([UserRole::NFL_EDITOR->value, UserRole::MAR_EDITOR->value]);

    $nflManuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 1, // NFL
    ]);

    $marManuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'region_id' => 2, // MAR
    ]);

    $glfManuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 3, // GLF (not assigned)
    ]);

    // Can access NFL manuscript
    $response = $this->actingAs($multiRegionEditor)
        ->getJson("/api/manuscript-records/{$nflManuscript->id}")
        ->assertOk();
    $response->assertOk();

    // Can access MAR manuscript
    $response = $this->actingAs($multiRegionEditor)
        ->getJson("/api/manuscript-records/{$marManuscript->id}")
        ->assertOk();
    $response->assertOk();

    // Cannot access GLF manuscript
    $this->actingAs($multiRegionEditor)
        ->getJson("/api/manuscript-records/{$glfManuscript->id}")
        ->assertForbidden();
});

test('regional editor permissions are correctly returned in response', function () {
    $ncrEditor = User::factory()->create();
    $ncrEditor->assignRole(UserRole::NCR_EDITOR->value);

    $draftManuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 8, // NCR region
    ]);

    $inReviewManuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'region_id' => 8, // NCR region
    ]);

    // Check draft permissions (can view and edit)
    $response = $this->actingAs($ncrEditor)
        ->getJson("/api/manuscript-records/{$draftManuscript->id}")
        ->assertOk();

    $response->assertOk();
    expect($response->json('can.update'))->toBe(true);
    expect($response->json('can.delete'))->toBe(false); // Regional editors cannot delete

    // Check in_review permissions (can view and edit)
    $response = $this->actingAs($ncrEditor)
        ->getJson("/api/manuscript-records/{$inReviewManuscript->id}")
        ->assertOk();

    $response->assertOk();
    expect($response->json('can.update'))->toBe(true);
    expect($response->json('can.delete'))->toBe(false);
});

test('regular author cannot access other users manuscripts without proper sharing', function () {
    $author = User::factory()->create();
    $author->assignRole(UserRole::AUTHOR->value);

    $otherUserManuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 1,
    ]);

    $this->actingAs($author)
        ->getJson("/api/manuscript-records/{$otherUserManuscript->id}")
        ->assertForbidden();
});

test('unauthenticated user cannot access any manuscript', function () {
    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'region_id' => 1,
    ]);

    $this->getJson("/api/manuscript-records/{$manuscript->id}")
        ->assertUnauthorized();
});
