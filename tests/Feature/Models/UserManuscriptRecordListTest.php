<?php

use App\Models\ManagementReviewStep;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\User;

test('an authenticated users can get a list of their manuscripts', function () {
    $manuscripts = ManuscriptRecord::factory()->count(5)->create();
    $user = User::factory()->create();
    $manuscripts = ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(2))->count(5)->count(5)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/my/manuscript-records')->assertOk();

    expect($response->json('data'))->toHaveCount(5);
    // expect manuscript author to be included
    expect($response->json('data.0.data.manuscript_authors'))->toHaveCount(2);
});

test('an authenticated user can get a list of manuscript without the ones they reviewed', function(){
    $reviewer = User::factory()->create();
    ManuscriptRecord::factory()->count(2)->create(['user_id' => $reviewer->id]);
    ManagementReviewStep::factory()->count(2)->create(['user_id' => $reviewer->id]);
    
    $response = $this->actingAs($reviewer)->getJson('/api/my/manuscript-records?include-reviews=true&sort=-title')->assertOk();
    expect($response->json('data'))->toHaveCount(4);

    $response = $this->actingAs($reviewer)->getJson('/api/my/manuscript-records')->assertOk();
    expect($response->json('data'))->toHaveCount(2);
});
