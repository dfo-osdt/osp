<?php

use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

test('peer reviewers without a role or type are backfilled as reviewers of a review', function (): void {
    $manuscript = ManuscriptRecord::factory()->secondary()->create();
    $legacy = ManuscriptReviewContributor::factory()->create(['manuscript_record_id' => $manuscript->id]);
    $editor = ManuscriptReviewContributor::factory()->editor()->create(['manuscript_record_id' => $manuscript->id]);
    DB::table('manuscript_review_contributors')->where('id', $legacy->id)->update(['role' => null, 'type' => null]);
    Schema::rename('manuscript_review_contributors', 'manuscript_peer_reviewers');

    (require database_path('migrations/2026_11_06_132644_rename_manuscript_peer_reviewers_to_manuscript_review_contributors.php'))->up();

    expect(Schema::hasTable('manuscript_peer_reviewers'))->toBeFalse()
        ->and(DB::table('manuscript_review_contributors')->where('id', $legacy->id)->first(['role', 'type']))
        ->toEqual((object) ['role' => 'reviewer', 'type' => 'review'])
        ->and(DB::table('manuscript_review_contributors')->where('id', $editor->id)->value('role'))->toBe('editor');
});
