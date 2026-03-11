<?php

use App\Actions\Expertise\CreateExpertise;
use App\Models\Expertise;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

// --- CreateExpertise Action Tests ---

it('creates an expertise with is_validated set to false', function (): void {
    $user = User::factory()->create();

    $expertise = CreateExpertise::handle([
        'name_en' => 'Quantum Computing',
        'name_fr' => 'Informatique quantique',
    ], $user);

    expect($expertise)->toBeInstanceOf(Expertise::class);
    expect($expertise->name_en)->toBe('Quantum Computing');
    expect($expertise->name_fr)->toBe('Informatique quantique');
    expect($expertise->is_validated)->toBeFalse();
});

it('logs activity when creating expertise', function (): void {
    $user = User::factory()->create();

    $expertise = CreateExpertise::handle([
        'name_en' => 'Data Science',
        'name_fr' => 'Science des données',
    ], $user);

    $activity = Activity::query()
        ->where('subject_type', Expertise::class)
        ->where('subject_id', $expertise->id)
        ->where('causer_id', $user->id)
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->description)->toBe('expertise.created');
    expect($activity->properties->toArray())->toMatchArray([
        'name_en' => 'Data Science',
        'name_fr' => 'Science des données',
    ]);
});

// --- Store Endpoint Tests ---

it('requires authentication to create expertise', function (): void {
    $response = $this->postJson('/api/expertises', [
        'name_en' => 'Test Expertise',
        'name_fr' => 'Expertise de test',
    ]);

    $response->assertUnauthorized();
});

it('allows authenticated user to create expertise', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/expertises', [
        'name_en' => 'Neuroscience',
        'name_fr' => 'Neurosciences',
    ]);

    $response->assertSuccessful();
    $response->assertJsonPath('data.name_en', 'Neuroscience');
    $response->assertJsonPath('data.name_fr', 'Neurosciences');
    $response->assertJsonPath('data.is_validated', false);

    $this->assertDatabaseHas('expertises', [
        'name_en' => 'Neuroscience',
        'name_fr' => 'Neurosciences',
        'is_validated' => false,
    ]);
});

it('requires name_en and name_fr to create expertise', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/expertises', []);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['name_en', 'name_fr']);
});

it('requires unique name_en when creating expertise', function (): void {
    $user = User::factory()->create();

    Expertise::factory()->create(['name_en' => 'Existing Expertise']);

    $response = $this->actingAs($user)->postJson('/api/expertises', [
        'name_en' => 'Existing Expertise',
        'name_fr' => 'Expertise unique',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['name_en']);
});

it('requires unique name_fr when creating expertise', function (): void {
    $user = User::factory()->create();

    Expertise::factory()->create(['name_fr' => 'Expertise existante']);

    $response = $this->actingAs($user)->postJson('/api/expertises', [
        'name_en' => 'Unique Expertise',
        'name_fr' => 'Expertise existante',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['name_fr']);
});

// --- Similar Endpoint Tests ---

it('requires authentication to access similar endpoint', function (): void {
    $response = $this->getJson('/api/expertises/similar?name_en=Test');

    $response->assertUnauthorized();
});

it('returns similar expertise matches', function (): void {
    $user = User::factory()->create();

    Expertise::factory()->validated()->create([
        'name_en' => 'Machine Learning',
        'name_fr' => 'Apprentissage automatique',
    ]);

    $response = $this->actingAs($user)->getJson('/api/expertises/similar?name_en=Machine+Learning');

    $response->assertSuccessful();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.expertise.data.name_en', 'Machine Learning');
    $response->assertJsonPath('data.0.score', 100);
});

it('returns empty array when no similar expertise found', function (): void {
    $user = User::factory()->create();

    Expertise::factory()->validated()->create([
        'name_en' => 'Marine Biology',
        'name_fr' => 'Biologie marine',
    ]);

    $response = $this->actingAs($user)->getJson('/api/expertises/similar?name_en=Quantum+Computing');

    $response->assertSuccessful();
    $response->assertJsonCount(0, 'data');
});

it('returns empty array when no query parameters provided', function (): void {
    $user = User::factory()->create();

    Expertise::factory()->validated()->create([
        'name_en' => 'Marine Biology',
        'name_fr' => 'Biologie marine',
    ]);

    $response = $this->actingAs($user)->getJson('/api/expertises/similar');

    $response->assertSuccessful();
    $response->assertJsonCount(0, 'data');
});

it('searches by french name via similar endpoint', function (): void {
    $user = User::factory()->create();

    Expertise::factory()->validated()->create([
        'name_en' => 'Artificial Intelligence',
        'name_fr' => 'Intelligence artificielle',
    ]);

    $response = $this->actingAs($user)->getJson('/api/expertises/similar?name_fr=Intelligence+artificielle');

    $response->assertSuccessful();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.expertise.data.name_fr', 'Intelligence artificielle');
});
