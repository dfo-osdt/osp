<?php

use App\Enums\ManuscriptRecordType;
use App\Mail\ManuscriptManagementReviewUnsubmittedMail;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Support\Facades\DB;

test('creating a manuscript stores the title as canonical markup', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/manuscript-records', [
        'title' => 'Growth of <em>Salmo salar</em>&nbsp;&amp; CO<span style="vertical-align: sub;">2</span>',
        'region_id' => 1,
        'type' => ManuscriptRecordType::PRIMARY->value,
    ])->assertCreated();

    expect(ManuscriptRecord::query()->find($response->json('data.id'))->title)
        ->toBe('Growth of <i>Salmo salar</i> &amp; CO<sub>2</sub>');
});

test('updating a manuscript with invalid title markup returns 422 and keeps the title', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id, 'title' => 'Growth of salmon']);

    $this->actingAs($user)->putJson("/api/manuscript-records/{$manuscript->id}", [
        'title' => '<i>Salmo salar',
    ])->assertUnprocessable()->assertJsonValidationErrors(['title' => 'The title has an unclosed <i> tag.']);

    expect($manuscript->refresh()->title)->toBe('Growth of salmon');
});

test('creating a publication with unsupported title markup returns 422', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson('/api/publications', [
        'status' => 'accepted',
        'title' => 'Growth of <b>salmon</b>',
        'journal_id' => Journal::factory()->create()->id,
        'accepted_on' => '2021-01-01',
        'region_id' => 1,
    ])->assertUnprocessable()->assertJsonValidationErrors(['title' => 'The title may only use italic, superscript and subscript formatting.']);

    expect(Publication::query()->count())->toBe(0);
});

test('searching manuscripts matches text across formatting and escaped characters', function (string $search): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'title' => '<i>Salmo salar</i> populations & CO<sub>2</sub>',
    ]);
    ManuscriptRecord::factory()->create(['user_id' => $user->id, 'title' => 'Cod stocks']);

    $response = $this->actingAs($user)->getJson('/api/my/manuscript-records?filter[search]='.urlencode($search))->assertOk();

    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.data.id'))->toBe($manuscript->id);
})->with([
    'across a closing tag' => 'salar populations',
    'an escaped ampersand' => 'populations & CO2',
]);

test('publications sort by title text rather than markup', function (): void {
    $user = User::factory()->create();
    $zebrafish = Publication::factory()->published()->create(['title' => '<i>Zebrafish</i> growth']);
    $atlantic = Publication::factory()->published()->create(['title' => 'Atlantic cod']);

    $response = $this->actingAs($user)->getJson('/api/publications?sort=title')->assertOk();

    expect(collect($response->json('data'))->pluck('data.id')->all())->toBe([$atlantic->id, $zebrafish->id]);
});

test('manuscript emails render the formatted title in the body and plain text in the subject', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['title' => '<i>Salmo salar</i> &amp; CO<sub>2</sub>']);

    $mail = new ManuscriptManagementReviewUnsubmittedMail($manuscript, $user, $user, 'Needs revisions');

    $mail->assertHasSubject("Manuscript Unsubmitted from Manuscript Management Review - Manuscrit retiré de l'examen de gestion du manuscrit : Salmo salar & CO2");
    // the mail CSS inliner adds style attributes to the tags
    $mail->assertSeeInOrderInHtml(['<i ', 'Salmo salar</i> &amp; CO<sub ', '2</sub>'], false);
});

test('manuscript emails escape legacy titles stored without sanitizing', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create();
    DB::table('manuscript_records')->where('id', $manuscript->id)->update(['title' => 'Growth <script>alert(1)</script>at <5cm']);

    $mail = new ManuscriptManagementReviewUnsubmittedMail($manuscript->refresh(), $user, $user, 'Needs revisions');

    $mail->assertDontSeeInHtml('<script>', false);
    $mail->assertSeeInHtml('Growth at', false);
});
