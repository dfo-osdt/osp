<?php

use App\Enums\ManuscriptRecordStatus;
use App\Enums\Permissions\UserRole;
use App\Models\ManuscriptRecord;
use App\Models\User;

test('regional observer can view draft manuscript in their region', function (): void {
    $nflObserver = User::factory()->create();
    $nflObserver->assignRole(UserRole::NFL_OBSERVER->value);

    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 1, // NFL region
    ]);

    $response = $this->actingAs($nflObserver)
        ->getJson("/api/manuscript-records/{$manuscript->id}")
        ->assertOk();

    expect($response->json('data.id'))->toBe($manuscript->id);
});

test('regional observer can view in_review manuscript in their region', function (): void {
    $marObserver = User::factory()->create();
    $marObserver->assignRole(UserRole::MAR_OBSERVER->value);

    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'region_id' => 2, // MAR region
    ]);

    $response = $this->actingAs($marObserver)
        ->getJson("/api/manuscript-records/{$manuscript->id}")
        ->assertOk();

    expect($response->json('data.id'))->toBe($manuscript->id);
});

test('regional observer cannot view manuscript outside their region', function (): void {
    $nflObserver = User::factory()->create();
    $nflObserver->assignRole(UserRole::NFL_OBSERVER->value);

    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 2, // MAR region (not NFL)
    ]);

    $this->actingAs($nflObserver)
        ->getJson("/api/manuscript-records/{$manuscript->id}")
        ->assertForbidden();
});

test('regional observer cannot edit manuscript via policy', function (): void {
    $queObserver = User::factory()->create();
    $queObserver->assignRole(UserRole::QUE_OBSERVER->value);

    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 4, // QUE region
    ]);

    // Observer should not have update permission
    expect($queObserver->can('update', $manuscript))->toBeFalse();
});

test('regional observer can see manuscripts from their region via scope', function (): void {
    $arcObserver = User::factory()->create();
    $arcObserver->assignRole(UserRole::ARC_OBSERVER->value);

    $arcRegion = \App\Models\Region::query()->where('slug', 'arc')->firstOrFail();
    $marRegion = \App\Models\Region::query()->where('slug', 'mar')->firstOrFail();

    // Create manuscripts in ARC region
    $manuscript1 = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => $arcRegion->id,
    ]);
    $manuscript2 = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'region_id' => $arcRegion->id,
    ]);

    // Create manuscript in different region
    $manuscript3 = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => $marRegion->id,
    ]);

    // Observer should be able to view manuscripts in their region
    expect($arcObserver->can('view', $manuscript1))->toBeTrue()
        ->and($arcObserver->can('view', $manuscript2))->toBeTrue()
        ->and($arcObserver->can('view', $manuscript3))->toBeFalse();
});

test('regional observer cannot see manuscripts from other regions in list', function (): void {
    $pacObserver = User::factory()->create();
    $pacObserver->assignRole(UserRole::PAC_OBSERVER->value);

    // Create manuscript in different region
    $manuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => 2, // MAR region (not PAC)
    ]);

    $response = $this->actingAs($pacObserver)
        ->getJson('/api/manuscript-records')
        ->assertOk();

    $manuscriptIds = collect($response->json('data'))->pluck('id')->all();

    expect($manuscriptIds)->not->toContain($manuscript->id);
});

test('user with multiple regional observer roles can access manuscripts from all regions via policy', function (): void {
    $multiObserver = User::factory()->create();
    $multiObserver->assignRole([UserRole::NFL_OBSERVER->value, UserRole::MAR_OBSERVER->value]);

    $nflRegion = \App\Models\Region::query()->where('slug', 'nfl')->firstOrFail();
    $marRegion = \App\Models\Region::query()->where('slug', 'mar')->firstOrFail();
    $queRegion = \App\Models\Region::query()->where('slug', 'que')->firstOrFail();

    $nflManuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => $nflRegion->id,
    ]);

    $marManuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'region_id' => $marRegion->id,
    ]);

    $queManuscript = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::DRAFT,
        'region_id' => $queRegion->id,
    ]);

    // Should be able to view NFL and MAR manuscripts, but not QUE
    expect($multiObserver->can('view', $nflManuscript))->toBeTrue()
        ->and($multiObserver->can('view', $marManuscript))->toBeTrue()
        ->and($multiObserver->can('view', $queManuscript))->toBeFalse();
});

test('regional observer permissions are view-only', function (): void {
    $ncrObserver = User::factory()->create();
    $ncrObserver->assignRole(UserRole::NCR_OBSERVER->value);

    $permissions = $ncrObserver->getAllPermissions()->pluck('name')->all();

    // Should have view permission
    expect($permissions)->toContain('can_view_ncr_mrfs')
        // Should NOT have edit permissions
        ->and($permissions)->not->toContain('can_edit_ncr_mrfs')
        ->and($permissions)->not->toContain('can_edit_ncr_pubs');
});

test('regional observer has correct role label', function (): void {
    expect(UserRole::NFL_OBSERVER->label())->toBe('Newfoundland and Labrador Observer')
        ->and(UserRole::MAR_OBSERVER->label())->toBe('Maritimes Observer')
        ->and(UserRole::GLF_OBSERVER->label())->toBe('Gulf Observer')
        ->and(UserRole::QUE_OBSERVER->label())->toBe('Quebec Observer')
        ->and(UserRole::ONP_OBSERVER->label())->toBe('Ontario and Prairie Observer')
        ->and(UserRole::ARC_OBSERVER->label())->toBe('Arctic Observer')
        ->and(UserRole::PAC_OBSERVER->label())->toBe('Pacific Observer')
        ->and(UserRole::NCR_OBSERVER->label())->toBe('National Capital Region Observer');
});
