<?php

use App\Events\ItemShared;
use App\Mail\ManuscriptRecordSharedMail;
use App\Models\ManuscriptRecord;
use App\Models\Shareable;
use App\Models\User;

test('a manuscript user can list who has access to one of their manuscript', function (): void {

    $manuscript = ManuscriptRecord::factory()->create();
    $shared = Shareable::factory()->create([
        'shareable_id' => $manuscript->id,
        'shared_by' => $manuscript->user_id,
    ]);
    $user = $manuscript->user;

    $this->actingAs($user)->getJson('/api/manuscript-records/'.$manuscript->id.'/sharing')
        ->assertOk()
        ->assertJsonCount(1, 'data');

    // test that shared user can view the manuscript
    $sharedUser = $shared->user;
    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/'.$manuscript->id)
        ->assertOk();

    // test that shared user can see the sharing details
    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/'.$manuscript->id.'/sharing')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

test('a shared user can view and edit a shared manuscript', function (): void {

    $manuscript = ManuscriptRecord::factory()->create();
    $shared = Shareable::factory()->create([
        'shareable_id' => $manuscript->id,
        'shared_by' => $manuscript->user_id,
        'can_edit' => false,
    ]);
    $sharedUser = $shared->user;

    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/'.$manuscript->id)
        ->assertOk();

    $this->actingAs($sharedUser)->putJson('/api/manuscript-records/'.$manuscript->id, [
        'title' => 'New Title',
    ])->assertForbidden();

    $shared->update(['can_edit' => true]);

    $this->actingAs($sharedUser)->putJson('/api/manuscript-records/'.$manuscript->id, [
        'title' => 'New Title',
    ])->assertOk();

    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/'.$manuscript->id)
        ->assertOk()
        ->assertJsonFragment([
            'title' => 'New Title',
        ]);
});

test('a shared user can no longer view the manuscript once the shareable has expired', function (): void {
    $shared = Shareable::factory()->create([
        'expires_at' => now()->subDays(5),
    ]);
    $sharedUser = $shared->user;
    $manuscript = $shared->shareable;

    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/'.$manuscript->id)
        ->assertForbidden();

    // update the shareable to not expire
    $shared->update(['expires_at' => null]);

    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/'.$manuscript->id)
        ->assertOk();
});

test('a shared user can be given the ability to delete the manuscript', function (): void {
    $shared = Shareable::factory()->create([
        'can_delete' => false,
    ]);

    $sharedUser = $shared->user;
    $manuscript = $shared->shareable;

    $this->actingAs($sharedUser)->deleteJson('/api/manuscript-records/'.$manuscript->id)
        ->assertForbidden();

    $shared->update(['can_delete' => true]);

    $this->actingAs($sharedUser)->deleteJson('/api/manuscript-records/'.$manuscript->id)
        ->assertStatus(204);
});

test('a user can share their manuscript with a user', function (): void {

    $manuscript = ManuscriptRecord::factory()->create();
    $user = $manuscript->user;
    $sharedUser = User::factory()->create();

    Event::fake();

    $response = $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/sharing', [
        'user_id' => $sharedUser->id,
        'can_edit' => true,
        'can_delete' => true,
        'expires_at' => now()->addDays(5),
    ])->assertCreated();

    Event::assertDispatched(fn (ItemShared $event): bool => $event->shareableItem->shareable->is($manuscript) && $event->shareableItem->user->is($sharedUser));

    $this->assertDatabaseHas('shareables', [
        'shareable_type' => ManuscriptRecord::class,
        'shareable_id' => $manuscript->id,
        'user_id' => $sharedUser->id,
        'shared_by' => $user->id,
        'can_edit' => true,
        'can_delete' => true,
    ]);

    $response->assertJsonFragment([
        'can_edit' => true,
        'can_delete' => true,
    ]);

    // test that the shared user cannot edit this shareable
    $this->actingAs($sharedUser)->postJson('/api/manuscript-records/'.$manuscript->id.'/sharing', [
        'user_id' => User::factory()->create()->id,
        'can_edit' => true,
        'can_delete' => true,
        'expires_at' => null,
    ])->assertForbidden();
});

test('a user can update a share to their manuscript', function (): void {

    $manuscript = ManuscriptRecord::factory()->create();
    $user = $manuscript->user;
    $sharedUser = User::factory()->create();
    $shareable = Shareable::factory()->create([
        'shareable_type' => ManuscriptRecord::class,
        'shareable_id' => $manuscript->id,
        'user_id' => $sharedUser->id,
        'shared_by' => $user->id,
        'can_edit' => true,
        'can_delete' => true,
    ]);

    $this->actingAs($user)->putJson('/api/manuscript-records/'.$manuscript->id.'/sharing/'.$shareable->id, [
        'can_edit' => false,
        'can_delete' => false,
    ])->assertOk();

    $this->assertDatabaseHas('shareables', [
        'id' => $shareable->id,
        'can_edit' => false,
        'can_delete' => false,
    ]);
});

test('a user can delete a share and shared user no longer has access', function (): void {

    $manuscript = ManuscriptRecord::factory()->create();
    $user = $manuscript->user;
    $sharedUser = User::factory()->create();
    $shareable = Shareable::factory()->create([
        'shareable_type' => ManuscriptRecord::class,
        'shareable_id' => $manuscript->id,
        'user_id' => $sharedUser->id,
        'shared_by' => $user->id,
        'can_edit' => true,
        'can_delete' => true,
    ]);

    $this->actingAs($user)->deleteJson('/api/manuscript-records/'.$manuscript->id.'/sharing/'.$shareable->id)
        ->assertNoContent();

    $this->assertDatabaseMissing('shareables', [
        'id' => $shareable->id,
    ]);

    $this->actingAs($sharedUser)->getJson('/api/manuscript-records/'.$manuscript->id)
        ->assertForbidden();
});

test('a user can see a single manuscript shareable entity', function (): void {
    $manuscript = ManuscriptRecord::factory()->create();
    $user = $manuscript->user;
    $sharedUser = User::factory()->create();
    $shareable = Shareable::factory()->create([
        'shareable_type' => ManuscriptRecord::class,
        'shareable_id' => $manuscript->id,
        'user_id' => $sharedUser->id,
        'shared_by' => $user->id,
        'can_edit' => true,
        'can_delete' => true,
    ]);

    $this->actingAs($user)->getJson('/api/manuscript-records/'.$manuscript->id.'/sharing/'.$shareable->id)
        ->assertOk()
        ->assertJsonFragment([
            'can_edit' => true,
            'can_delete' => true,
        ]);
});

test('a shared manuscript is seen in my manuscript for a shared users', function (): void {

    $manuscript = ManuscriptRecord::factory()->create();
    $user = $manuscript->user;
    $sharedUser = User::factory()->create();
    $shareable = Shareable::factory()->create([
        'shareable_type' => ManuscriptRecord::class,
        'shareable_id' => $manuscript->id,
        'user_id' => $sharedUser->id,
        'shared_by' => $user->id,
        'can_edit' => true,
        'can_delete' => true,
    ]);

    $response = $this->actingAs($sharedUser)->getJson('/api/my/manuscript-records')
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);

    // shouldn't show if the shareable is expired
    $shareable->update(['expires_at' => now()->subDays(5)]);

    $this->actingAs($sharedUser)->getJson('/api/my/manuscript-records')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

test('a email is sent when a sharing event for a manuscript is dispatched', function (): void {

    $sharable = Shareable::factory()->create();

    Mail::fake();
    event(new \App\Events\ItemShared($sharable));
    Mail::assertQueued(ManuscriptRecordSharedMail::class);
});

test('the share email is properly formatted', function (): void {

    $shareable = Shareable::factory()->create([
        'can_edit' => true,
        'can_delete' => true,
        'expires_at' => now()->addDays(5),
    ]);

    $mailable = new ManuscriptRecordSharedMail($shareable);

    $mailable->assertTo($shareable->user->email);
    $mailable->assertSeeInHtml($shareable->shareable->title);
    $mailable->assertSeeInHtml(now()->addDays(5)->format('Y-m-d'));

    // check with null expires_at
    $shareable->update(['expires_at' => null]);

    ray($mailable);
    $mailable = new ManuscriptRecordSharedMail($shareable);
    $mailable->assertSeeInHtml('Never');
});

test('a user cannot share the same manuscript twice to the same user', function (): void {

    $manuscript = ManuscriptRecord::factory()->create();
    $user = $manuscript->user;
    $sharedUser = User::factory()->create();

    $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/sharing', [
        'user_id' => $sharedUser->id,
        'can_edit' => true,
        'can_delete' => true,
        'expires_at' => '2023-11-17T18:22:52.080Z',
    ])->assertCreated();

    $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/sharing', [
        'user_id' => $sharedUser->id,
        'can_edit' => true,
        'can_delete' => true,
        'expires_at' => null,
    ])->assertOk();

    // should only be one shareable, with updated expiry...
    $this->assertDatabaseCount('shareables', 1);
    $this->assertDatabaseHas('shareables', [
        'shareable_type' => ManuscriptRecord::class,
        'shareable_id' => $manuscript->id,
        'user_id' => $sharedUser->id,
        'shared_by' => $user->id,
        'can_edit' => true,
        'can_delete' => true,
        'expires_at' => null,
    ]);
});
