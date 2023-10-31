<?php

use App\Models\ManuscriptRecord;
use App\Models\Shareable;

test('a manuscript user can share one of their manuscript', function () {

    $manuscript = ManuscriptRecord::factory()->create();
    $shared = Shareable::factory()->create([
        'shareable_id' => $manuscript->id,
        'shared_by' => $manuscript->user_id,
    ]);
    $user = $manuscript->user;

    $this->actingAs($user)->getJson('/api/manuscript-records/' . $manuscript->id . '/sharing')
        ->assertOk()
        ->assertJsonCount(1, 'data');

    // test that shared user can view the manuscript
    $sharedUser = $shared->user;
    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/' . $manuscript->id)
        ->assertOk();
});

test('a shared user can view and edit a shared manuscript', function () {

    $manuscript = ManuscriptRecord::factory()->create();
    $shared = Shareable::factory()->create([
        'shareable_id' => $manuscript->id,
        'shared_by' => $manuscript->user_id,
        'can_edit' => false,
    ]);
    $sharedUser = $shared->user;

    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/' . $manuscript->id)
        ->assertOk();

    $this->actingAs($sharedUser)->putJson('/api/manuscript-records/' . $manuscript->id, [
        'title' => 'New Title',
    ])->assertForbidden();

    $shared->update(['can_edit' => true]);

    $this->actingAs($sharedUser)->putJson('/api/manuscript-records/' . $manuscript->id, [
        'title' => 'New Title',
    ])->assertOk();

    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/' . $manuscript->id)
        ->assertOk()
        ->assertJsonFragment([
            'title' => 'New Title',
        ]);
});

test('a shared user can no longer view the manuscript once the shareable has expired', function () {
    $shared = Shareable::factory()->create([
        'expires_at' => now()->subDays(5),
    ]);
    $sharedUser = $shared->user;
    $manuscript = $shared->shareable;

    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/' . $manuscript->id)
        ->assertForbidden();

    // update the shareable to not expire
    $shared->update(['expires_at' => null]);

    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/' . $manuscript->id)
        ->assertOk();
});

test('a shared user can be given the ability to delete the manuscript', function () {
    $shared = Shareable::factory()->create([
        'can_delete' => false,
    ]);

    $sharedUser = $shared->user;
    $manuscript = $shared->shareable;

    $this->actingAs($sharedUser)->deleteJson('/api/manuscript-records/' . $manuscript->id)
        ->assertForbidden();

    $shared->update(['can_delete' => true]);

    $this->actingAs($sharedUser)->deleteJson('/api/manuscript-records/' . $manuscript->id)
        ->assertStatus(204);
});

test('a user can share their manuscript with a user', function () {

    $manuscript = ManuscriptRecord::factory()->create();
    $user = $manuscript->user;

    $this->actingAs($user)->postJson('/api/manuscript-records/' . $manuscript->id . '/sharing', [
        'user_id' => $user->id,
        'can_edit' => true,
        'can_delete' => true,
        'expires_at' => now()->addDays(5),
    ])->assertCreated();
});
