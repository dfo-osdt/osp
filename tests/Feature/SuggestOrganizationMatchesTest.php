<?php

use App\Actions\Organizations\SuggestOrganizationMatches;
use App\Models\Organization;

it('returns empty collection when no unvalidated organizations exist', function (): void {
    Organization::factory()->create();

    $results = SuggestOrganizationMatches::handle();

    expect($results)->toBeEmpty();
});

it('finds matches based on similar english names', function (): void {
    $rorOrg = Organization::factory()->create([
        'name_en' => 'University of Toronto',
        'name_fr' => 'Université de Toronto',
        'is_validated' => true,
        'ror_identifier' => 'https://ror.org/03dbr7087',
        'ror_names' => json_encode([
            ['value' => 'University of Toronto', 'types' => ['ror_display'], 'lang' => null],
            ['value' => 'University of Toronto', 'types' => ['label'], 'lang' => 'en'],
            ['value' => 'Université de Toronto', 'types' => ['label'], 'lang' => 'fr'],
            ['value' => 'UofT', 'types' => ['acronym'], 'lang' => null],
        ]),
    ]);

    $manualOrg = Organization::factory()->create([
        'name_en' => 'University of Toronto',
        'name_fr' => 'University of Toronto',
        'is_validated' => false,
        'ror_identifier' => null,
    ]);

    $results = SuggestOrganizationMatches::handle();

    expect($results)->toHaveCount(1);
    expect($results->first()['source']->id)->toBe($manualOrg->id);
    expect($results->first()['matches'])->toHaveCount(1);
    expect($results->first()['matches']->first()['organization']->id)->toBe($rorOrg->id);
    expect($results->first()['matches']->first()['score'])->toBe(100);
});

it('finds matches based on similar french names', function (): void {
    $rorOrg = Organization::factory()->create([
        'name_en' => 'University of Montreal',
        'name_fr' => 'Université de Montréal',
        'is_validated' => true,
        'ror_identifier' => 'https://ror.org/0123456789',
        'ror_names' => json_encode([
            ['value' => 'Université de Montréal', 'types' => ['ror_display'], 'lang' => null],
            ['value' => 'Université de Montréal', 'types' => ['label'], 'lang' => 'fr'],
        ]),
    ]);

    $manualOrg = Organization::factory()->create([
        'name_en' => 'Universite de Montreal',
        'name_fr' => 'Universite de Montreal',
        'is_validated' => false,
        'ror_identifier' => null,
    ]);

    $results = SuggestOrganizationMatches::handle();

    expect($results)->toHaveCount(1);
    expect($results->first()['matches'])->not->toBeEmpty();
    expect($results->first()['matches']->first()['organization']->id)->toBe($rorOrg->id);
});

it('respects minimum score threshold', function (): void {
    Organization::factory()->create([
        'name_en' => 'University of Toronto',
        'name_fr' => 'Université de Toronto',
        'is_validated' => true,
        'ror_identifier' => 'https://ror.org/03dbr7087',
        'ror_names' => json_encode([
            ['value' => 'University of Toronto', 'types' => ['ror_display'], 'lang' => null],
        ]),
    ]);

    Organization::factory()->create([
        'name_en' => 'Something Completely Different',
        'name_fr' => 'Quelque chose de différent',
        'is_validated' => false,
        'ror_identifier' => null,
    ]);

    $results = SuggestOrganizationMatches::handle(minimumScore: 90);

    expect($results->first()['matches'])->toBeEmpty();
});

it('matches against ror name variants', function (): void {
    $rorOrg = Organization::factory()->create([
        'name_en' => 'Massachusetts Institute of Technology',
        'name_fr' => 'Massachusetts Institute of Technology',
        'abbr_en' => 'MIT',
        'is_validated' => true,
        'ror_identifier' => 'https://ror.org/042nb2s44',
        'ror_names' => json_encode([
            ['value' => 'Massachusetts Institute of Technology', 'types' => ['ror_display'], 'lang' => null],
            ['value' => 'MIT', 'types' => ['acronym'], 'lang' => null],
        ]),
    ]);

    Organization::factory()->create([
        'name_en' => 'MIT',
        'name_fr' => 'MIT',
        'is_validated' => false,
        'ror_identifier' => null,
    ]);

    $results = SuggestOrganizationMatches::handle();

    expect($results->first()['matches'])->not->toBeEmpty();
    expect($results->first()['matches']->first()['organization']->id)->toBe($rorOrg->id);
});

it('limits suggestions per organization', function (): void {
    for ($i = 1; $i <= 5; $i++) {
        Organization::factory()->create([
            'name_en' => "University of Testing Campus {$i}",
            'name_fr' => "Université de Testing Campus {$i}",
            'is_validated' => true,
            'ror_identifier' => "https://ror.org/0test000{$i}",
            'ror_names' => json_encode([
                ['value' => "University of Testing Campus {$i}", 'types' => ['ror_display'], 'lang' => null],
            ]),
        ]);
    }

    Organization::factory()->create([
        'name_en' => 'University of Testing',
        'name_fr' => 'Université de Testing',
        'is_validated' => false,
        'ror_identifier' => null,
    ]);

    $results = SuggestOrganizationMatches::handle(maxSuggestions: 3);

    expect($results->first()['matches'])->toHaveCount(3);
});
