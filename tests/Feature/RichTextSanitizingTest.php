<?php

use App\Mail\ManuscriptManagementReviewUnsubmittedMail;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Support\Facades\DB;

test('updating a manuscript stores sanitized rich text', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->putJson("/api/manuscript-records/{$manuscript->id}", [
        'abstract' => '<b>Results</b><img src=x onerror="alert(1)"><script>alert(2)</script>',
        'pls_en' => '<a href="javascript:alert(1)">link</a>',
    ])->assertOk();

    $manuscript->refresh();
    expect($manuscript->abstract)->toBe('<b>Results</b>')
        ->and($manuscript->pls_en)->toBe('<a rel="noopener noreferrer">link</a>');
});

test('updating review comments stores sanitized rich text', function (): void {
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer))->create();
    $step = $manuscript->managementReviewSteps->first();

    $this->actingAs($reviewer)->putJson("/api/manuscript-records/{$manuscript->id}/management-review-steps/{$step->id}", [
        'comments' => '<i>Revise</i> <iframe src="https://example.com"></iframe>',
    ])->assertOk();

    expect($step->refresh()->comments)->toBe('<i>Revise</i> ');
});

test('manuscript emails sanitize legacy abstracts stored without sanitizing', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create();
    DB::table('manuscript_records')->where('id', $manuscript->id)->update(['abstract' => '<b>Results</b><img src=x onerror="alert(1)">']);

    $mail = new ManuscriptManagementReviewUnsubmittedMail($manuscript->refresh(), $user, $user, 'Needs revisions');

    $mail->assertDontSeeInHtml('onerror', false);
    $mail->assertSeeInHtml('Results</b>', false);
});
