<?php

use App\Models\AuthorEmployment;
use App\Models\Organization;
use App\Models\User;

test('a user can list their author profile employements', function () {
    $user = User::factory()->create();
    $employments = AuthorEmployment::factory()->count(2)->create([
        'author_id' => $user->author->id,
        'organization_id' => $user->author->organization->id,
    ]);

    $response = $this->actingAs($user)->getJson("api/authors/{$user->author->id}/employments")->assertOk();

    expect($response->json('data'))->toHaveCount(2);

    // try to access another author's employments
    $anotherUser = User::factory()->create();
    $response = $this->actingAs($anotherUser)->getJson("api/authors/{$user->author->id}/employments")->assertForbidden();

});

test('a user can see a single profile employement', function () {
    $user = User::factory()->create();
    $employment = AuthorEmployment::factory()->create([
        'author_id' => $user->author->id,
        'organization_id' => $user->author->organization->id,
    ]);

    $response = $this->actingAs($user)->getJson("api/authors/{$user->author->id}/employments/{$employment->id}")->assertOk();

    expect($response->json('data.id'))->toBe($employment->id);

    // try to access another author's employment
    $anotherUser = User::factory()->create();
    $response = $this->actingAs($anotherUser)->getJson("api/authors/{$user->author->id}/employments/{$employment->id}")->assertForbidden();

});

test('a user can create a new author employment record', function () {

    // mock queue to ensure job dispatched
    Queue::fake();

    $user = User::factory()->create();

    $data = AuthorEmployment::factory()->make([
        'author_id' => $user->author->id,
        'organization_id' => Organization::getDefaultOrganization()->id,
        'start_date' => now()->subYear(),
        'end_date' => null,
        'role_title' => 'Software Developer',
        'department_name' => 'Engineering',
    ])->toArray();

    $response = $this->actingAs($user)->postJson("api/authors/{$user->author->id}/employments", $data)->assertCreated();

    expect($response->json('data.author_id'))->toBe($user->author->id);

    // try to create with a different organization
    $data['organization_id'] = Organization::factory()->create()->id;
    $response = $this->actingAs($user)->postJson("api/authors/{$user->author->id}/employments", $data)->assertForbidden();

    Queue::assertPushed(\App\Jobs\SyncAuthorEmploymentWithOrcid::class);

    // try to create with another user
    $anotherUser = User::factory()->create();
    $response = $this->actingAs($anotherUser)->postJson("api/authors/{$user->author->id}/employments", $data)->assertForbidden();

});

test('a user can update an author employment record', function () {

    Queue::fake();

    $user = User::factory()->create();
    $employment = AuthorEmployment::factory()->create([
        'author_id' => $user->author->id,
        'organization_id' => Organization::getDefaultOrganization()->id,
        'start_date' => now()->subYear(),
    ]);

    $data = AuthorEmployment::factory()->make([
        'author_id' => $user->author->id,
        'organization_id' => Organization::getDefaultOrganization()->id,
        'start_date' => now()->subYear(),
        'end_date' => now(),
        'role_title' => 'Software Developer',
        'department_name' => 'Engineering',
    ])->toArray();

    $response = $this->actingAs($user)->putJson("api/authors/{$user->author->id}/employments/{$employment->id}", $data)->assertOk();

    expect($response->json('data.role_title'))->toBe('Software Developer');

    Queue::assertPushed(\App\Jobs\SyncAuthorEmploymentWithOrcid::class);

    // try to update with another user
    $anotherUser = User::factory()->create();
    $response = $this->actingAs($anotherUser)->putJson("api/authors/{$user->author->id}/employments/{$employment->id}", $data)->assertForbidden();

});

test('a user can delete an author employment record', function () {

    Queue::fake();
    $user = User::factory()->create();
    $employment = AuthorEmployment::factory()->create([
        'author_id' => $user->author->id,
        'organization_id' => Organization::getDefaultOrganization()->id,
        'start_date' => now()->subYear(),
    ]);
    // try to delete with another user
    $anotherUser = User::factory()->create();
    $response = $this->actingAs($anotherUser)->deleteJson("api/authors/{$user->author->id}/employments/{$employment->id}")->assertForbidden();

    $response = $this->actingAs($user)->deleteJson("api/authors/{$user->author->id}/employments/{$employment->id}")->assertNoContent();
    Queue::assertPushed(\App\Jobs\SyncAuthorEmploymentWithOrcid::class);

});

test('adding a 125 char role title is valid', function () {
    $user = User::factory()->create();
    $employment = AuthorEmployment::factory()->create([
        'author_id' => $user->author->id,
        'organization_id' => Organization::getDefaultOrganization()->id,
        'start_date' => now()->subYear(),
    ]);

    $data = AuthorEmployment::factory()->make([
        'author_id' => $user->author->id,
        'organization_id' => Organization::getDefaultOrganization()->id,
        'start_date' => now()->subYear(),
        'role_title' => str_repeat('a', 125),
    ])->toArray();

    $response = $this->actingAs($user)->putJson("api/authors/{$user->author->id}/employments/{$employment->id}", $data)->assertOk();

    expect($response->json('data.role_title'))->toBe(str_repeat('a', 125));

});
