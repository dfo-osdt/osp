<?php

use App\Enums\Permissions\UserRole;
use App\Models\ManagementReviewStep;
use App\Models\User;

test('a user can only see their own management review steps', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    ManagementReviewStep::factory()->count(3)->create(['user_id' => $user->id]);
    ManagementReviewStep::factory()->count(2)->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->getJson('/api/my/management-review-steps')->assertOk();

    expect($response->json('data'))->toHaveCount(3);
});

test('a user with view-any permission only sees their own management review steps', function (): void {
    $director = User::factory()->withRoles([UserRole::DIRECTOR])->create();
    $otherUser = User::factory()->create();

    ManagementReviewStep::factory()->count(2)->create(['user_id' => $director->id]);
    ManagementReviewStep::factory()->count(5)->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($director)->getJson('/api/my/management-review-steps')->assertOk();

    expect($response->json('data'))->toHaveCount(2);
});

test('an admin only sees their own management review steps', function (): void {
    $admin = User::factory()->withRoles([UserRole::ADMIN])->create();
    $otherUser = User::factory()->create();

    ManagementReviewStep::factory()->count(1)->create(['user_id' => $admin->id]);
    ManagementReviewStep::factory()->count(4)->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($admin)->getJson('/api/my/management-review-steps')->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});
