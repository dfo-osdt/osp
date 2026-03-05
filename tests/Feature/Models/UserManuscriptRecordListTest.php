<?php

use App\Enums\ManuscriptRecordStatus;
use App\Models\Author;
use App\Models\FunctionalArea;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use App\Models\User;

test('an authenticated users can get a list of their manuscripts', function (): void {
    $manuscripts = ManuscriptRecord::factory()->count(5)->create();
    $user = User::factory()->create();
    $manuscripts = ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(2))->count(5)->count(5)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/my/manuscript-records')->assertOk();

    expect($response->json('data'))->toHaveCount(5);
    // expect manuscript author to be included
    expect($response->json('data.0.data.manuscript_authors'))->toHaveCount(2);
});

test('an authenticated user can get a list of manuscript without the ones they reviewed', function (): void {
    $reviewer = User::factory()->create();
    ManuscriptRecord::factory()->count(2)->create(['user_id' => $reviewer->id]);
    ManagementReviewStep::factory()->count(2)->create(['user_id' => $reviewer->id]);

    $response = $this->actingAs($reviewer)->getJson('/api/my/manuscript-records?include-reviews=true&sort=-title')->assertOk();
    expect($response->json('data'))->toHaveCount(4);

    $response = $this->actingAs($reviewer)->getJson('/api/my/manuscript-records')->assertOk();
    expect($response->json('data'))->toHaveCount(2);
});

test('a user can see all their manuscript and filter them', function (): void {
    $user = User::factory()->create();
    // create a manuscript where the user is the applicant
    $manuscript = ManuscriptRecord::factory()->filled()->create(['user_id' => $user->id]);

    // create a manuscript where the user is an author
    $manuscript2 = ManuscriptRecord::factory()->filled()->create();
    $manuscript2->manuscriptAuthors()->create([
        'author_id' => Author::factory()->create(['user_id' => $user->id])->id,
        'organization_id' => Organization::factory()->create()->id,
    ]);

    // create a review step where the user is the reviewer
    ManagementReviewStep::factory()->create(['user_id' => $user->id]);

    // query the user's manuscripts
    $response = $this->actingAs($user)->getJson('/api/my/manuscript-records?include-reviews=true')->assertOk();
    expect($response->json('data'))->toHaveCount(3);

    // filter by status - just draft
    $response = $this->actingAs($user)->getJson('/api/my/manuscript-records?filter[status]='.ManuscriptRecordStatus::DRAFT->value)->assertOk();
    expect($response->json('data'))->toHaveCount(2);

    $manuscript2->status = ManuscriptRecordStatus::ACCEPTED;
    $manuscript2->save();

    // filter by status - just in review
    $response = $this->actingAs($user)->getJson('/api/my/manuscript-records?include-reviews=true&filter[status]='.ManuscriptRecordStatus::IN_REVIEW->value)->assertOk();
    expect($response->json('data'))->toHaveCount(1);

    // filter by status - just accepted
    $response = $this->actingAs($user)->getJson('/api/my/manuscript-records?include-reviews=true&filter[status]='.ManuscriptRecordStatus::ACCEPTED->value)->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

test('can filter my manuscripts by functional_area_id', function (): void {
    $user = User::factory()->create();
    $functionalArea = FunctionalArea::factory()->create();
    $otherFunctionalArea = FunctionalArea::factory()->create();

    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'functional_area_id' => $functionalArea->id,
    ]);

    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'functional_area_id' => $otherFunctionalArea->id,
    ]);

    $response = $this->actingAs($user)->getJson("/api/my/manuscript-records?filter[functional_area_id]={$functionalArea->id}");

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

test('can filter my manuscripts by reviewedBetween date range', function (): void {
    $user = User::factory()->create();

    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'reviewed_at' => '2025-06-15',
    ]);

    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'reviewed_at' => '2025-01-01',
    ]);

    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'reviewed_at' => '2025-12-01',
    ]);

    $response = $this->actingAs($user)->getJson('/api/my/manuscript-records?filter[reviewedBetween]=2025-06-01,2025-06-30');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.data.reviewed_at'))->toContain('2025-06-15');
});
