<?php

use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

function runSanitizeExistingRichTextMigration(): void
{
    (require database_path('migrations/2026_11_02_145545_sanitize_existing_rich_text.php'))->up();
}

test('existing rich text is sanitized and each changed row is logged with its previous values', function (): void {
    $manuscript = ManuscriptRecord::factory()->create();
    $step = ManagementReviewStep::factory()->create();
    DB::table('manuscript_records')->where('id', $manuscript->id)->update([
        'abstract' => '<b>Results</b><img src=x onerror="alert(1)">',
        'pls_en' => '<i>Clean</i>',
    ]);
    DB::table('management_review_steps')->where('id', $step->id)->update(['comments' => 'Fix<script>alert(1)</script>']);
    $updatedAt = DB::table('manuscript_records')->where('id', $manuscript->id)->value('updated_at');

    runSanitizeExistingRichTextMigration();

    $row = DB::table('manuscript_records')->where('id', $manuscript->id)->first();
    expect($row->abstract)->toBe('<b>Results</b>')
        ->and($row->pls_en)->toBe('<i>Clean</i>')
        ->and($row->updated_at)->toBe($updatedAt)
        ->and(DB::table('management_review_steps')->where('id', $step->id)->value('comments'))->toBe('Fix');

    $logged = Activity::query()->where('description', 'Sanitized rich text')->get();
    expect($logged->map(fn (Activity $activity): array => $activity->properties->all())->all())->toBe([
        ['table' => 'manuscript_records', 'id' => $manuscript->id, 'old' => ['abstract' => '<b>Results</b><img src=x onerror="alert(1)">']],
        ['table' => 'management_review_steps', 'id' => $step->id, 'old' => ['comments' => 'Fix<script>alert(1)</script>']],
    ]);
});
