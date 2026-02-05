<?php

use App\Models\ManuscriptRecord;
use App\Models\Publication;

it('syncs accepted_on date to manuscript record when publication is updated', function () {
    // Create a manuscript with an accepted date
    $manuscript = ManuscriptRecord::factory()->create([
        'accepted_on' => '2024-01-15',
    ]);

    // Create a publication from the manuscript
    $publication = Publication::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'accepted_on' => '2024-01-15',
    ]);

    // Update the publication's accepted_on date
    $publication->update([
        'accepted_on' => '2024-01-20',
    ]);

    // The manuscript's accepted_on should be synced
    expect($manuscript->fresh()->accepted_on)
        ->toBe('2024-01-20');
});

it('does not affect manuscript when publication has no manuscript_record_id', function () {
    // Create a standalone publication (no manuscript)
    $publication = Publication::factory()->create([
        'manuscript_record_id' => null,
        'accepted_on' => '2024-01-15',
    ]);

    // Update the publication - should not throw an error
    $publication->update([
        'accepted_on' => '2024-01-20',
    ]);

    expect($publication->accepted_on->format('Y-m-d'))->toBe('2024-01-20');
});

it('handles null accepted_on when updating publication', function () {
    $manuscript = ManuscriptRecord::factory()->create([
        'accepted_on' => '2024-01-15',
    ]);

    $publication = Publication::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'accepted_on' => '2024-01-15',
    ]);

    // Update to null
    $publication->update([
        'accepted_on' => null,
    ]);

    // Manuscript should also be null
    expect($manuscript->fresh()->accepted_on)->toBeNull();
});
