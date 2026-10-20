<?php

use App\Models\Sector;
use App\Models\User;
use Database\Seeders\SectorSeeder;

test('an unauthenticated user cannot list the sectors', function (): void {
    $this->getJson('/api/sectors')->assertUnauthorized();
});

test('an authenticated user can list the seeded DFO sectors', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/sectors');

    $response->assertOk();
    expect($response->json('data.*.data.name_en'))->toBe([
        'Aquatic Ecosystems',
        'Conservation and Protection',
        'Ecosystems and Oceans Science',
        'Fisheries and Harbour Management',
        'Strategic Policy',
    ]);
});

test('the sector seeder does not duplicate existing sectors', function (): void {
    $this->seed(SectorSeeder::class);

    expect(Sector::query()->count())->toBe(5);
});
