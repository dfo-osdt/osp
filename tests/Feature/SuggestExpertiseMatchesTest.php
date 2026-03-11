<?php

use App\Actions\Expertise\SuggestExpertiseMatches;
use App\Models\Expertise;

it('returns empty collection when no names are provided', function (): void {
    Expertise::factory()->create();

    $results = SuggestExpertiseMatches::handle();

    expect($results)->toBeEmpty();
});

it('finds matches based on similar english names', function (): void {
    $existing = Expertise::factory()->validated()->create([
        'name_en' => 'Marine Biology',
        'name_fr' => 'Biologie marine',
    ]);

    $results = SuggestExpertiseMatches::handle(nameEn: 'Marine Biology');

    expect($results)->toHaveCount(1);
    expect($results->first()['expertise']->id)->toBe($existing->id);
    expect($results->first()['score'])->toBe(100);
});

it('finds matches based on similar french names', function (): void {
    $existing = Expertise::factory()->validated()->create([
        'name_en' => 'Marine Biology',
        'name_fr' => 'Biologie marine',
    ]);

    $results = SuggestExpertiseMatches::handle(nameFr: 'Biologie marine');

    expect($results)->toHaveCount(1);
    expect($results->first()['expertise']->id)->toBe($existing->id);
    expect($results->first()['score'])->toBe(100);
});

it('finds matches across both languages', function (): void {
    $existing = Expertise::factory()->validated()->create([
        'name_en' => 'Artificial Intelligence',
        'name_fr' => 'Intelligence artificielle',
    ]);

    $results = SuggestExpertiseMatches::handle(
        nameEn: 'Artificial Intelligence Research',
        nameFr: 'Recherche en intelligence artificielle',
    );

    expect($results)->not->toBeEmpty();
    expect($results->first()['expertise']->id)->toBe($existing->id);
});

it('respects minimum score threshold', function (): void {
    Expertise::factory()->validated()->create([
        'name_en' => 'Marine Biology',
        'name_fr' => 'Biologie marine',
    ]);

    $results = SuggestExpertiseMatches::handle(
        nameEn: 'Quantum Computing',
        minimumScore: 90,
    );

    expect($results)->toBeEmpty();
});

it('limits the number of suggestions returned', function (): void {
    for ($i = 1; $i <= 6; $i++) {
        Expertise::factory()->validated()->create([
            'name_en' => "Data Science Variant {$i}",
            'name_fr' => "Science des données variante {$i}",
        ]);
    }

    $results = SuggestExpertiseMatches::handle(
        nameEn: 'Data Science',
        maxSuggestions: 3,
    );

    expect($results)->toHaveCount(3);
});

it('returns results sorted by score descending', function (): void {
    Expertise::factory()->validated()->create([
        'name_en' => 'Marine Biology',
        'name_fr' => 'Biologie marine',
    ]);

    Expertise::factory()->validated()->create([
        'name_en' => 'Marine Biotechnology',
        'name_fr' => 'Biotechnologie marine',
    ]);

    $results = SuggestExpertiseMatches::handle(nameEn: 'Marine Biology');

    expect($results)->toHaveCount(2);
    expect($results->first()['score'])->toBeGreaterThanOrEqual($results->last()['score']);
});

it('includes matched_on field in results', function (): void {
    Expertise::factory()->validated()->create([
        'name_en' => 'Machine Learning',
        'name_fr' => 'Apprentissage automatique',
    ]);

    $results = SuggestExpertiseMatches::handle(nameEn: 'Machine Learning');

    expect($results)->toHaveCount(1);
    expect($results->first()['matched_on'])->toContain('name_en');
});

it('also matches against unvalidated expertise', function (): void {
    $unvalidated = Expertise::factory()->create([
        'name_en' => 'Deep Learning',
        'name_fr' => 'Apprentissage profond',
        'is_validated' => false,
    ]);

    $results = SuggestExpertiseMatches::handle(nameEn: 'Deep Learning');

    expect($results)->toHaveCount(1);
    expect($results->first()['expertise']->id)->toBe($unvalidated->id);
});
