<?php

use App\Enums\OrcidSyncIntent;
use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Enums\PeerReviewRole;

test('an user can list the review contributors to their secondary manuscript', function (): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $manuscript->reviewContributors()->saveMany(ManuscriptReviewContributor::factory()->count(2)->create());

    // wrong user can't access the manuscript review contributors
    $response = $this->actingAs(User::factory()->create())->getJson('api/manuscript-records/'.$manuscript->id.'/review-contributors');

    $response = $this->actingAs($user)->getJson('api/manuscript-records/'.$manuscript->id.'/review-contributors');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(2);
});

test('the review contributor list says which contributions are on ORCID', function (): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $onOrcid = ManuscriptReviewContributor::factory()->create(['manuscript_record_id' => $manuscript->id]);
    $onOrcid->forceFill(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 4567, 'orcid_updated_at' => now()])->save();
    $notOnOrcid = ManuscriptReviewContributor::factory()->create(['manuscript_record_id' => $manuscript->id]);

    $response = $this->actingAs($user)->getJson('api/manuscript-records/'.$manuscript->id.'/review-contributors');

    $response->assertOk();
    $synced = collect($response->json('data'))->mapWithKeys(fn (array $contributor): array => [
        $contributor['data']['id'] => $contributor['data']['orcid_synced'],
    ]);
    expect($synced->all())->toBe([$onOrcid->id => true, $notOnOrcid->id => false]);
});

test('a user can add a review contributor with a role to their secondary manuscript', function (string $role): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $author = Author::factory()->create();

    $data = [
        'author_id' => $author->id,
        'role' => $role,
    ];
    // wrong user can't add a review contributor to the manuscript
    $response = $this->actingAs(User::factory()->create())->postJson('api/manuscript-records/'.$manuscript->id.'/review-contributors', $data);

    $response = $this->actingAs($user)->postJson('api/manuscript-records/'.$manuscript->id.'/review-contributors', $data);

    $response->assertCreated();
    expect($response->json('data.author_id'))->toBe($author->id)
        ->and($response->json('data.role'))->toBe($role)
        ->and($manuscript->reviewContributors()->count())->toBe(1);

    $activity = Activity::query()->where('description', 'manuscript.review-contributor.created')->sole();
    expect($activity->subject->is($manuscript))->toBeTrue()
        ->and($activity->causer->is($user))->toBeTrue()
        ->and($activity->properties->all())->toBe(['author_id' => $author->id, 'role' => $role]);
})->with(['reviewer', 'editor']);

test('a review contributor needs a reviewer or editor role', function (?string $role): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->postJson('api/manuscript-records/'.$manuscript->id.'/review-contributors', [
        'author_id' => Author::factory()->create()->id,
        'role' => $role,
    ]);

    $response->assertUnprocessable()->assertJsonValidationErrors('role');
    expect($manuscript->reviewContributors()->count())->toBe(0);
})->with([null, 'chair', 'member', 'organizer', 'not-a-role']);

test('a user cannot add a review contributor to their primary manuscript', function (): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->create();

    $data = [
        'author_id' => $author->id,
        'role' => 'reviewer',
    ];

    $response = $this->actingAs($user)->postJson('api/manuscript-records/'.$manuscript->id.'/review-contributors', $data);

    $response->assertStatus(422);
});

test('a user can change the role of a review contributor on their secondary manuscript', function (): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $contributor = ManuscriptReviewContributor::factory()->create(['manuscript_record_id' => $manuscript->id]);

    $response = $this->actingAs($user)->putJson('api/manuscript-records/'.$manuscript->id.'/review-contributors/'.$contributor->id, [
        'role' => 'editor',
    ]);

    $response->assertOk();
    expect($response->json('data.role'))->toBe('editor')
        ->and($contributor->fresh()->role)->toBe(PeerReviewRole::Editor);

    $activity = Activity::query()->where('description', 'manuscript.review-contributor.updated')->sole();
    expect($activity->subject->is($manuscript))->toBeTrue()
        ->and($activity->causer->is($user))->toBeTrue()
        ->and($activity->properties->all())->toBe([
            'author_id' => $contributor->author_id,
            'role' => 'editor',
            'old_role' => 'reviewer',
        ]);
});

test('a review contributor role cannot be changed to one outside reviewer or editor', function (): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $contributor = ManuscriptReviewContributor::factory()->create(['manuscript_record_id' => $manuscript->id]);

    $response = $this->actingAs($user)->putJson('api/manuscript-records/'.$manuscript->id.'/review-contributors/'.$contributor->id, [
        'role' => 'chair',
    ]);

    $response->assertUnprocessable()->assertJsonValidationErrors('role');
    expect($contributor->fresh()->role)->toBe(PeerReviewRole::Reviewer);
});

test('a user can remove a review contributor from their secondary manuscript', function (): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $contributor = ManuscriptReviewContributor::factory()->editor()->create(['manuscript_record_id' => $manuscript->id]);

    // wrong user can't remove a review contributor from the manuscript
    $response = $this->actingAs(User::factory()->create())->deleteJson('api/manuscript-records/'.$manuscript->id.'/review-contributors/'.$contributor->id);

    $response = $this->actingAs($user)->deleteJson('api/manuscript-records/'.$manuscript->id.'/review-contributors/'.$contributor->id);

    $response->assertStatus(204);
    expect($manuscript->reviewContributors()->count())->toBe(0);

    $activity = Activity::query()->where('description', 'manuscript.review-contributor.removed')->sole();
    expect($activity->subject->is($manuscript))->toBeTrue()
        ->and($activity->causer->is($user))->toBeTrue()
        ->and($activity->properties->all())->toBe(['author_id' => $contributor->author_id, 'role' => 'editor']);
});

test('a user cannot change the review contributors if they can only view the manuscript', function (): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->in_review()->create(['user_id' => $user->id]);
    $contributor = ManuscriptReviewContributor::factory()->create(['manuscript_record_id' => $manuscript->id]);

    $author = Author::factory()->create();

    $data = [
        'author_id' => $author->id,
        'role' => 'reviewer',
    ];

    $response = $this->actingAs($user)->postJson('api/manuscript-records/'.$manuscript->id.'/review-contributors', $data);
    $response->assertStatus(403);

    $response = $this->actingAs($user)->putJson('api/manuscript-records/'.$manuscript->id.'/review-contributors/'.$contributor->id, ['role' => 'editor']);
    $response->assertForbidden();

    $response = $this->actingAs($user)->deleteJson('api/manuscript-records/'.$manuscript->id.'/review-contributors/'.$contributor->id);
    $response->assertStatus(403);

    // act as active reviwer - can change the review contributors
    $response = $this->actingAs($manuscript->managementReviewSteps()->first()->user)->postJson('api/manuscript-records/'.$manuscript->id.'/review-contributors', $data);
});

test('a user cannot reach a review contributor through another manuscript they can edit', function (): void {

    $user = User::factory()->create();
    $ownManuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $contributor = ManuscriptReviewContributor::factory()->create([
        'manuscript_record_id' => ManuscriptRecord::factory()->secondary()->create()->id,
    ]);
    $url = 'api/manuscript-records/'.$ownManuscript->id.'/review-contributors/'.$contributor->id;

    $this->actingAs($user)->getJson($url)->assertNotFound();
    $this->actingAs($user)->putJson($url, ['role' => 'editor'])->assertNotFound();
    $this->actingAs($user)->deleteJson($url)->assertNotFound();

    expect($contributor->fresh()->role)->toBe(PeerReviewRole::Reviewer)
        ->and(Activity::query()->where('description', 'like', 'manuscript.review-contributor.%')->count())->toBe(0);
});

test('a user cannot add a review contributor that already exists or that is on the list of manuscript authors', function (): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $author = Author::factory()->create();
    $manuscript->manuscriptAuthors()->save(ManuscriptAuthor::factory()->create(['author_id' => $author->id]));

    $data = [
        'author_id' => $author->id,
        'role' => 'reviewer',
    ];

    $response = $this->actingAs($user)->postJson('api/manuscript-records/'.$manuscript->id.'/review-contributors', $data);

    $response->assertStatus(422);
});

test('a user cannot add a review contributor to a manuscript twice, even with another role', function (): void {

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->create(['user_id' => $user->id]);
    $author = Author::factory()->create();

    $response = $this->actingAs($user)->postJson('api/manuscript-records/'.$manuscript->id.'/review-contributors', [
        'author_id' => $author->id,
        'role' => 'reviewer',
    ]);
    $response->assertStatus(201);
    $response = $this->actingAs($user)->postJson('api/manuscript-records/'.$manuscript->id.'/review-contributors', [
        'author_id' => $author->id,
        'role' => 'editor',
    ]);
    $response->assertStatus(422);
});
