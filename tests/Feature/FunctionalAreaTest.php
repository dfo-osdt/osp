<?php

use App\Models\User;

test('an authenticated user can get a list of the functional areas', function (): void {

    $user = User::factory()->create();

    $response = $this->getJson('/api/functional-areas');

    $response->assertUnauthorized();

    $response = $this->actingAs($user)->getJson('/api/functional-areas');

    ray($response->json());
    $response->assertOk();
    $areaCount = \App\Models\FunctionalArea::query()->count();
    expect($response->json('data'))->toHaveCount($areaCount);
});
