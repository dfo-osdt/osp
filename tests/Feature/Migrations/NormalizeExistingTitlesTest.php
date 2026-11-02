<?php

use App\Models\ManuscriptRecord;
use App\Models\Publication;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

function runNormalizeExistingTitlesMigration(): void
{
    (require database_path('migrations/2026_11_02_141528_normalize_existing_titles.php'))->up();
}

test('existing titles are rewritten to canonical markup and each change is logged', function (): void {
    $manuscript = ManuscriptRecord::factory()->create();
    $publication = Publication::factory()->create();
    DB::table('manuscript_records')->where('id', $manuscript->id)->update(['title' => 'Ontario & Prairie Region']);
    DB::table('publications')->where('id', $publication->id)->update(['title' => 'Sea Pens: The Cases of <i>Ptilella</i> in  Newfoundland']);
    $updatedAt = DB::table('publications')->where('id', $publication->id)->value('updated_at');

    runNormalizeExistingTitlesMigration();

    expect(DB::table('manuscript_records')->where('id', $manuscript->id)->value('title'))->toBe('Ontario &amp; Prairie Region')
        ->and(DB::table('publications')->where('id', $publication->id)->value('title'))->toBe('Sea Pens: The Cases of <i>Ptilella</i> in Newfoundland')
        ->and(DB::table('publications')->where('id', $publication->id)->value('updated_at'))->toBe($updatedAt);

    $logged = Activity::query()->where('description', 'Normalized title to formatted HTML')->get();
    expect($logged->map(fn (Activity $activity): array => $activity->properties->only(['table', 'id', 'old_title'])->all())->all())->toBe([
        ['table' => 'manuscript_records', 'id' => $manuscript->id, 'old_title' => 'Ontario & Prairie Region'],
        ['table' => 'publications', 'id' => $publication->id, 'old_title' => 'Sea Pens: The Cases of <i>Ptilella</i> in  Newfoundland'],
    ]);
});

test('titles already in canonical form are not rewritten or logged', function (): void {
    ManuscriptRecord::factory()->create(['title' => 'Growth of <i>Salmo salar</i> &amp; trout']);
    $loggedBefore = Activity::query()->count();

    runNormalizeExistingTitlesMigration();

    expect(Activity::query()->where('description', 'Normalized title to formatted HTML')->count())->toBe(0)
        ->and(Activity::query()->count())->toBe($loggedBefore);
});
