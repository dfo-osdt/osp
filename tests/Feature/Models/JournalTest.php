<?php

use App\Models\Journal;
use App\Models\User;
use Database\Seeders\JournalsTableSeeder;

test('a user can get a list of journals', function (): void {
    // create a user
    $user = User::factory()->create();

    // unauthenticated user cannot get a list of journals
    $response = $this->getJson('/api/journals')->assertUnauthorized();

    Journal::factory()->count(20)->create();

    // authenticated user can get a list of journals
    $response = $this->actingAs($user)->getJson('/api/journals')->assertOk();
    expect($response->json('data'))->toHaveCount(config('osp.api_pagination.default', 10));
    expect($response->json('meta.total'))->toBe(\App\Models\Journal::query()->count());
});

test('a user can get a list of all dfo journals', function (): void {
    // create a user
    $user = User::factory()->create();

    // Make 6 dfo journals
    Journal::factory()->count(6)->dfoSeries()->create();
    Journal::factory()->count(5)->create();

    // authenticated user can get a list of journals
    $response = $this->actingAs($user)->getJson('/api/journals?filter[dfo_series]=1&limit=20')->assertOk();

    expect($response->json('data'))->toHaveCount(6);
});

test('a user can get a specific journal by id', function (): void {
    // create a user
    $user = User::factory()->create();

    // create a journal
    $journal = Journal::factory()->create();

    // unauthenticated user cannot get a specific journal
    $response = $this->getJson('/api/journals/'.$journal->id)->assertUnauthorized();

    // authenticated user can get a specific journal
    $response = $this->actingAs($user)->getJson('/api/journals/'.$journal->id)->assertOk();
    expect($response->json('data.id'))->toBe($journal->id);
});

test('a user can find these specific journals by title', function (string $title): void {

    expect($title)->not->toBeEmpty();

    // seed the journals table - we want to use our actual seed data here
    $this->seed(JournalsTableSeeder::class);

    // make sure that title is in the database
    $this->assertDatabaseHas('journals', ['title' => $title]);

    // create a user
    $user = User::factory()->create();

    // search for a journal by title
    $response = $this->actingAs($user)->getJson('/api/journals?filter[search]='.$title.'&sort=title-length')->assertOk();

    $returnedTitles = collect($response->json('data'))->pluck('data.title');

    expect($returnedTitles)->toContain($title);

})->with(['Science', 'Fisheries', 'Ecology', 'Nature']);
