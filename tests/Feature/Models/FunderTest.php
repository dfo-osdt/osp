
<?php

use App\Models\Funder;
use App\Models\User;

test('a user can get a list of funders', function (): void {
    // This test expects the seed data to be loaded

    $user = User::factory()->create();
    $response = $this->getJson('/api/funders')->assertUnauthorized();
    $response = $this->actingAs($user)->getJson('/api/funders')->assertOk();
    expect($response->json('data'))->toHaveCount(Funder::count());
});

test('a user can get a single funder', function (): void {
    $user = User::factory()->create();
    $funder = Funder::factory()->create();
    $response = $this->getJson('/api/funders/'.$funder->id)->assertUnauthorized();
    $response = $this->actingAs($user)->getJson('/api/funders/'.$funder->id)->assertOk();
    expect($response->json('data.id'))->toBe($funder->id);
});
