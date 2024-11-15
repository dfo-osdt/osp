<?php

use App\Models\Author;
use App\Models\ManuscriptPeerReviewer;
use App\Models\ManuscriptRecord;
use App\Models\User;

test('an user can list the peer reviewer to their secondary manuscript', function () {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $manuscript->peerReviewers()->saveMany(ManuscriptPeerReviewer::factory()->count(2)->create());

    // wrong user can't access the manuscript peer reviewers
    $response = $this->actingAs(User::factory()->create())->getJson('api/manuscript-records/'.$manuscript->id.'/peer-reviewers');

    $response = $this->actingAs($user)->getJson('api/manuscript-records/'.$manuscript->id.'/peer-reviewers');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(2);
});

test('a user can add a peer reviewer to their secondary manuscript', function () {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $author = Author::factory()->create();

    $data = [
        'author_id' => $author->id,
    ];
    // wrong user can't add a peer reviewer to the manuscript
    $response = $this->actingAs(User::factory()->create())->postJson('api/manuscript-records/'.$manuscript->id.'/peer-reviewers', $data);

    $response = $this->actingAs($user)->postJson('api/manuscript-records/'.$manuscript->id.'/peer-reviewers', $data);

    $response->assertStatus(201);
    expect($response->json('data.author_id'))->toBe($author->id);
    expect($manuscript->peerReviewers()->count())->toBe(1);
});

test('a user cannot add a peer reviewer to their primary manuscript', function () {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->create();

    $data = [
        'author_id' => $author->id,
    ];

    $response = $this->actingAs($user)->postJson('api/manuscript-records/'.$manuscript->id.'/peer-reviewers', $data);

    $response->assertStatus(422);
});

test('a user can remove a peer reviewer from their secondary manuscript', function () {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $peerReviewer = ManuscriptPeerReviewer::factory()->create(['manuscript_record_id' => $manuscript->id]);

    // wrong user can't remove a peer reviewer from the manuscript
    $response = $this->actingAs(User::factory()->create())->deleteJson('api/manuscript-records/'.$manuscript->id.'/peer-reviewers/'.$peerReviewer->id);

    $response = $this->actingAs($user)->deleteJson('api/manuscript-records/'.$manuscript->id.'/peer-reviewers/'.$peerReviewer->id);

    $response->assertStatus(200);
    expect($manuscript->peerReviewers()->count())->toBe(0);
});
