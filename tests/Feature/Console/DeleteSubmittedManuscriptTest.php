<?php

use App\Enums\ManuscriptRecordStatus;
use App\Models\ManuscriptRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

test('it deletes an in_review manuscript and all related data', function (): void {
    $manuscript = ManuscriptRecord::factory()->in_review()->create();

    expect($manuscript->managementReviewSteps)->not->toBeEmpty();
    expect($manuscript->manuscriptAuthors)->not->toBeEmpty();
    expect($manuscript->fundingSources)->not->toBeEmpty();
    expect($manuscript->getManuscriptFiles())->not->toBeEmpty();

    $this->artisan('osp:delete-submitted-manuscript', ['id' => $manuscript->id, '--force' => true])
        ->assertExitCode(0)
        ->expectsOutput('Manuscript record and all associated data have been permanently deleted.');

    expect(ManuscriptRecord::withTrashed()->find($manuscript->id))->toBeNull();
    expect($manuscript->managementReviewSteps()->count())->toBe(0);
    expect($manuscript->manuscriptAuthors()->count())->toBe(0);
    expect($manuscript->fundingSources()->withTrashed()->count())->toBe(0);
});

test('it refuses to delete a manuscript not in IN_REVIEW status', function (ManuscriptRecordStatus $status): void {
    $manuscript = ManuscriptRecord::factory()->create(['status' => $status]);

    $this->artisan('osp:delete-submitted-manuscript', ['id' => $manuscript->id, '--force' => true])
        ->assertExitCode(1)
        ->expectsOutputToContain('not in IN_REVIEW status');

    expect(ManuscriptRecord::query()->find($manuscript->id))->not->toBeNull();
})->with([
    'draft' => ManuscriptRecordStatus::DRAFT,
    'reviewed' => ManuscriptRecordStatus::REVIEWED,
]);

test('it fails when manuscript id does not exist', function (): void {
    $this->artisan('osp:delete-submitted-manuscript', ['id' => 99999, '--force' => true])
        ->assertExitCode(1)
        ->expectsOutput('Manuscript record not found.');
});

test('it logs activity on successful deletion', function (): void {
    $manuscript = ManuscriptRecord::factory()->in_review()->create();

    $this->artisan('osp:delete-submitted-manuscript', ['id' => $manuscript->id, '--force' => true])
        ->assertExitCode(0);

    $activity = Activity::query()
        ->where('description', 'DeleteSubmittedManuscript command executed successfully')
        ->latest()
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->properties['manuscript_record_id'])->toBe($manuscript->id);
});
