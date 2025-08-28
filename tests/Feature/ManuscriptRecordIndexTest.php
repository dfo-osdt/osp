<?php

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Enums\Permissions\UserPermission;
use App\Enums\Permissions\UserRole;
use App\Models\ManuscriptRecord;
use App\Models\Region;
use App\Models\User;

// Authorization Tests
test('unauthenticated users cannot access manuscript index', function () {
    $response = $this->getJson('/api/manuscript-records');
    $response->assertUnauthorized();
});

test('users without system permissions get 403 forbidden', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertForbidden();
});

// Global Permission Tests
test('users with VIEW_ANY_MANUSCRIPT_RECORD see all non-draft manuscripts', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    // Create manuscripts with different statuses
    ManuscriptRecord::factory()->create(['status' => ManuscriptRecordStatus::DRAFT]);
    ManuscriptRecord::factory()->create(['status' => ManuscriptRecordStatus::IN_REVIEW]);
    ManuscriptRecord::factory()->create(['status' => ManuscriptRecordStatus::SUBMITTED]);
    ManuscriptRecord::factory()->create(['status' => ManuscriptRecordStatus::ACCEPTED]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(3); // All except draft
    
    // Verify no drafts are included
    $statuses = collect($response->json('data'))->pluck('data.status')->toArray();
    expect($statuses)->not->toContain(ManuscriptRecordStatus::DRAFT->value);
});

test('global permission users cannot see draft manuscripts', function () {
    $user = User::factory()->withRoles([UserRole::EDITOR])->create();
    
    ManuscriptRecord::factory()->count(3)->create(['status' => ManuscriptRecordStatus::DRAFT]);
    ManuscriptRecord::factory()->count(2)->create(['status' => ManuscriptRecordStatus::IN_REVIEW]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(2);
});

// Regional Permission Tests  
test('NFL editor can see all manuscripts from NFL region including drafts', function () {
    $user = User::factory()->withRoles([UserRole::NFL_EDITOR])->create();
    
    $nflRegion = Region::where('slug', 'nfl')->first();
    $marRegion = Region::where('slug', 'mar')->first();
    
    // NFL manuscripts (should see all including drafts)
    ManuscriptRecord::factory()->count(2)->create([
        'region_id' => $nflRegion->id,
        'status' => ManuscriptRecordStatus::DRAFT
    ]);
    ManuscriptRecord::factory()->count(1)->create([
        'region_id' => $nflRegion->id, 
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]);
    
    // MAR manuscripts (should not see any)
    ManuscriptRecord::factory()->count(3)->create([
        'region_id' => $marRegion->id,
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(3); // Only NFL manuscripts
    
    // Verify all returned manuscripts are from NFL region
    $regionIds = collect($response->json('data'))->pluck('data.region_id')->unique()->toArray();
    expect($regionIds)->toBe([$nflRegion->id]);
});

test('regional editors cannot see manuscripts from other regions', function () {
    $user = User::factory()->withRoles([UserRole::MAR_EDITOR])->create();
    
    $nflRegion = Region::where('slug', 'nfl')->first();
    $marRegion = Region::where('slug', 'mar')->first();
    
    // Create manuscripts in different regions
    ManuscriptRecord::factory()->count(2)->create(['region_id' => $nflRegion->id]);
    ManuscriptRecord::factory()->count(3)->create(['region_id' => $marRegion->id]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(3); // Only MAR manuscripts
});

test('regional editors see drafts from their region but not others', function () {
    $user = User::factory()->withRoles([UserRole::GLF_EDITOR])->create();
    
    $glfRegion = Region::where('slug', 'glf')->first();
    $queRegion = Region::where('slug', 'que')->first();
    
    // GLF drafts (should see)
    ManuscriptRecord::factory()->count(2)->create([
        'region_id' => $glfRegion->id,
        'status' => ManuscriptRecordStatus::DRAFT
    ]);
    
    // QUE drafts (should not see)
    ManuscriptRecord::factory()->count(1)->create([
        'region_id' => $queRegion->id,
        'status' => ManuscriptRecordStatus::DRAFT
    ]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(2);
    
    // Verify all are GLF region and draft status
    $manuscripts = collect($response->json('data'));
    expect($manuscripts->every(fn($ms) => $ms['data']['region_id'] === $glfRegion->id))->toBeTrue();
    expect($manuscripts->every(fn($ms) => $ms['data']['status'] === ManuscriptRecordStatus::DRAFT->value))->toBeTrue();
});

// Multi-Regional Permission Tests
test('user with multiple regional permissions sees manuscripts from all assigned regions', function () {
    // Create user with both NFL and MAR editor roles
    $user = User::factory()->create();
    $user->assignRole([UserRole::NFL_EDITOR, UserRole::MAR_EDITOR]);
    
    $nflRegion = Region::where('slug', 'nfl')->first();
    $marRegion = Region::where('slug', 'mar')->first();
    $glfRegion = Region::where('slug', 'glf')->first();
    
    // Create manuscripts in different regions
    ManuscriptRecord::factory()->count(2)->create(['region_id' => $nflRegion->id]);
    ManuscriptRecord::factory()->count(3)->create(['region_id' => $marRegion->id]);
    ManuscriptRecord::factory()->count(1)->create(['region_id' => $glfRegion->id]); // Should not see
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(5); // NFL + MAR only
    
    // Verify only NFL and MAR regions
    $regionIds = collect($response->json('data'))->pluck('data.region_id')->unique()->sort()->values()->toArray();
    expect($regionIds)->toBe([$nflRegion->id, $marRegion->id]);
});

// Combined Permission Tests
test('user with global AND regional permissions sees union of both access patterns', function () {
    // Create user with both global and regional permissions
    $user = User::factory()->create();
    $user->assignRole([UserRole::EDITOR, UserRole::ONP_EDITOR]); // Global + Regional
    
    $onpRegion = Region::where('slug', 'onp')->first();
    $arcRegion = Region::where('slug', 'arc')->first();
    
    // ONP region manuscripts (should see all including drafts due to regional permission)
    ManuscriptRecord::factory()->count(2)->create([
        'region_id' => $onpRegion->id,
        'status' => ManuscriptRecordStatus::DRAFT
    ]);
    
    // ARC region manuscripts (should see non-drafts due to global permission)
    ManuscriptRecord::factory()->count(1)->create([
        'region_id' => $arcRegion->id,
        'status' => ManuscriptRecordStatus::DRAFT
    ]); // Should NOT see this draft
    ManuscriptRecord::factory()->count(2)->create([
        'region_id' => $arcRegion->id,
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]); // Should see these
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(4); // 2 ONP drafts + 2 ARC non-drafts
});

// Filtering and Sorting Tests
test('manuscripts can be filtered by status', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    ManuscriptRecord::factory()->count(2)->create(['status' => ManuscriptRecordStatus::IN_REVIEW]);
    ManuscriptRecord::factory()->count(3)->create(['status' => ManuscriptRecordStatus::SUBMITTED]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records?filter[status]=' . ManuscriptRecordStatus::IN_REVIEW->value);
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(2);
});

test('manuscripts can be filtered by type', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    ManuscriptRecord::factory()->count(2)->create([
        'type' => ManuscriptRecordType::PRIMARY,
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]);
    ManuscriptRecord::factory()->count(1)->create([
        'type' => ManuscriptRecordType::SECONDARY,
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records?filter[type]=' . ManuscriptRecordType::PRIMARY->value);
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(2);
});

test('manuscripts can be filtered by region_id', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    $nflRegion = Region::where('slug', 'nfl')->first();
    $marRegion = Region::where('slug', 'mar')->first();
    
    ManuscriptRecord::factory()->count(2)->create([
        'region_id' => $nflRegion->id,
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]);
    ManuscriptRecord::factory()->count(1)->create([
        'region_id' => $marRegion->id,
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records?filter[region_id]=' . $nflRegion->id);
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(2);
});

test('manuscripts can be sorted by title', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    ManuscriptRecord::factory()->create([
        'title' => 'Zebra Research',
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]);
    ManuscriptRecord::factory()->create([
        'title' => 'Alpha Study',
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records?sort=title');
    $response->assertOk();
    
    $titles = collect($response->json('data'))->pluck('data.title')->toArray();
    expect($titles)->toBe(['Alpha Study', 'Zebra Research']);
});

test('manuscripts can be sorted by created_at descending', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    // Create manuscripts with known timestamps
    $older = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'created_at' => now()->subDays(2)
    ]);
    $newer = ManuscriptRecord::factory()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'created_at' => now()->subDay()
    ]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records?sort=-created_at');
    $response->assertOk();
    
    $ids = collect($response->json('data'))->pluck('data.id')->toArray();
    expect($ids)->toBe([$newer->id, $older->id]); // Newer first
});

test('manuscripts support partial text search on title', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    ManuscriptRecord::factory()->create([
        'title' => 'Climate Change Research',
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]);
    ManuscriptRecord::factory()->create([
        'title' => 'Ocean Biology Study',
        'status' => ManuscriptRecordStatus::IN_REVIEW
    ]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records?filter[title]=climate');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.data.title'))->toContain('Climate');
});

// Pagination Tests
test('manuscripts respect default pagination limit', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    // Create more manuscripts than the default limit
    ManuscriptRecord::factory()->count(15)->create(['status' => ManuscriptRecordStatus::IN_REVIEW]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertOk();
    
    // Should respect the default limit from config (10)
    expect($response->json('data'))->toHaveCount(10);
    expect($response->json('meta.per_page'))->toBe(10);
    expect($response->json('meta.total'))->toBe(15);
});

test('manuscripts respect custom limit parameter', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    ManuscriptRecord::factory()->count(25)->create(['status' => ManuscriptRecordStatus::IN_REVIEW]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records?limit=15');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(15);
    expect($response->json('meta.per_page'))->toBe(15);
});

test('manuscript pagination works with filtering', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    ManuscriptRecord::factory()->count(15)->create(['status' => ManuscriptRecordStatus::IN_REVIEW]);
    ManuscriptRecord::factory()->count(10)->create(['status' => ManuscriptRecordStatus::SUBMITTED]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records?filter[status]=' . ManuscriptRecordStatus::IN_REVIEW->value . '&limit=8');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(8);
    expect($response->json('meta.total'))->toBe(15); // Total filtered results
});

// Data Integrity Tests
test('response includes proper relationships', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    $manuscript = ManuscriptRecord::factory()->create(['status' => ManuscriptRecordStatus::IN_REVIEW]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertOk();
    
    $data = $response->json('data.0.data');
    
    // Verify relationships are loaded (should not be null if loaded)
    expect($data)->toHaveKeys(['user', 'region']);
});

test('response structure matches ManuscriptRecordResource format', function () {
    $user = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    
    ManuscriptRecord::factory()->create(['status' => ManuscriptRecordStatus::IN_REVIEW]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records');
    $response->assertOk();
    
    // Verify basic structure
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'data' => [
                    'id',
                    'title',
                    'status',
                    'type',
                    'region_id',
                    'created_at',
                    'updated_at',
                ]
            ]
        ],
        'links',
        'meta' => [
            'current_page',
            'per_page',
            'total',
            'last_page'
        ]
    ]);
});

// Combined Filter and Pagination Test
test('complex query with multiple filters and pagination works correctly', function () {
    $user = User::factory()->withRoles([UserRole::PAC_EDITOR])->create();
    
    $pacRegion = Region::where('slug', 'pac')->first();
    
    // Create variety of manuscripts in PAC region
    ManuscriptRecord::factory()->count(5)->create([
        'region_id' => $pacRegion->id,
        'type' => ManuscriptRecordType::PRIMARY,
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'title' => 'Marine Biology Research'
    ]);
    
    ManuscriptRecord::factory()->count(3)->create([
        'region_id' => $pacRegion->id,
        'type' => ManuscriptRecordType::SECONDARY,
        'status' => ManuscriptRecordStatus::IN_REVIEW,
        'title' => 'Marine Biology Research'
    ]);
    
    ManuscriptRecord::factory()->count(2)->create([
        'region_id' => $pacRegion->id,
        'type' => ManuscriptRecordType::PRIMARY,
        'status' => ManuscriptRecordStatus::SUBMITTED,
        'title' => 'Climate Study'
    ]);
    
    $response = $this->actingAs($user)->getJson('/api/manuscript-records?filter[type]=' . ManuscriptRecordType::PRIMARY->value . '&filter[title]=marine&sort=-created_at&limit=3');
    $response->assertOk();
    
    expect($response->json('data'))->toHaveCount(3);
    expect($response->json('meta.total'))->toBe(5); // 5 primary manuscripts with "marine" in title
});