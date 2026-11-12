<?php

use App\Enums\ManuscriptRecordType;
use App\Enums\OrcidSyncIntent;
use App\Enums\Permissions\UserRole;
use App\Enums\PublicationStatus;
use App\Mail\WorkPublishedMail;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use Database\Factories\AuthorFactory;
use Illuminate\Support\Facades\Mail;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Requests\Works\CreateWork;

beforeEach(function (): void {
    Mail::fake();
});

/**
 * An accepted report in a flagged DFO series, on a secondary manuscript
 */
function acceptedReportForWorks(): Publication
{
    return Publication::factory()->create([
        'title' => 'Stock assessment of Atlantic cod',
        'status' => PublicationStatus::ACCEPTED,
        'accepted_on' => now()->subMonth(),
        'doi' => null,
        'manuscript_record_id' => ManuscriptRecord::factory()->create(['type' => ManuscriptRecordType::SECONDARY])->id,
        'journal_id' => Journal::factory()->dfoSeries()->create(['title' => 'Science Response', 'issn' => '1919-5079', 'orcid_sync_enabled' => true])->id,
    ]);
}

/**
 * The given author on the publication, with a user account unless told otherwise
 */
function reportAuthor(Publication $publication, AuthorFactory $factory, ?OrcidSyncIntent $intent = null, bool $withUser = true): PublicationAuthor
{
    $author = $factory->create();

    if ($withUser) {
        User::factory()->create(['email' => $author->email]);
    }

    $publicationAuthor = PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => $author->id,
    ]);
    $publicationAuthor->forceFill(['orcid_sync' => $intent])->save();

    return $publicationAuthor;
}

function worksAutoSyncAuthor(): AuthorFactory
{
    return Author::factory()->withOrcidConnection()->state(['orcid_auto_sync_works' => true]);
}

/**
 * Publish the publication as a chief editor, who publishes DFO reports
 *
 * @param  array<string, mixed>  $attributes
 */
function publishReportForWorks(Publication $publication, array $attributes = ['doi' => 'https://doi.org/10.60825/abc-123']): User
{
    $chiefEditor = User::factory()->withRoles([UserRole::CHIEF_EDITOR])->create();

    test()->actingAs($chiefEditor)
        ->putJson("api/publications/{$publication->id}", [
            'status' => PublicationStatus::PUBLISHED->value,
            'published_on' => now()->toDateString(),
            ...$attributes,
        ])
        ->assertOk();

    return $chiefEditor;
}

test('publishing adds the work of authors with auto sync on to ORCID, logged on the publication', function (): void {
    $mockClient = Saloon::fake([CreateWork::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/work/4567'])]);
    $publication = acceptedReportForWorks();
    $publicationAuthor = reportAuthor($publication, worksAutoSyncAuthor());

    $chiefEditor = publishReportForWorks($publication);

    $mockClient->assertSentCount(1, CreateWork::class);
    $publicationAuthor->refresh();
    expect($publicationAuthor->orcid_sync)->toBe(OrcidSyncIntent::WANTED)
        ->and($publicationAuthor->orcid_putcode)->toBe(4567);
    Mail::assertNotQueued(WorkPublishedMail::class);

    $activity = Activity::query()->forSubject($publication)->where('description', 'publication.author.orcid-wanted')->sole();
    expect($activity->causer_id)->toBe($chiefEditor->id)
        ->and($activity->properties->all())->toBe(['author_id' => $publicationAuthor->author_id, 'orcid_sync' => 'wanted', 'auto_sync' => true]);
});

test('publishing emails an author connected to ORCID that they can add their publication', function (): void {
    Saloon::fake([]);
    $publication = acceptedReportForWorks();
    $publicationAuthor = reportAuthor($publication, Author::factory()->withOrcidConnection()->state(['email' => 'jane+orcid@dfo-mpo.gc.ca']));

    publishReportForWorks($publication);

    Mail::assertQueued(WorkPublishedMail::class, 1);
    Mail::assertQueued(WorkPublishedMail::class, function (WorkPublishedMail $mail) use ($publicationAuthor): bool {
        $mail->assertSeeInHtml('You can now add it to your ORCID record')
            ->assertSeeInHtml('Vous pouvez maintenant')
            ->assertSeeInHtml('Stock assessment of Atlantic cod')
            ->assertSeeInHtml('Science Response')
            ->assertDontSeeInHtml('connect your ORCID iD')
            ->assertSeeInHtml('email=jane%2Borcid%40dfo-mpo.gc.ca')
            ->assertSeeInHtml('redirect=%2Fsettings%2Fauthor%2Forcid-works');

        return $mail->hasTo('jane+orcid@dfo-mpo.gc.ca') && $mail->publicationAuthor->is($publicationAuthor);
    });
    expect($publicationAuthor->refresh()->orcid_sync)->toBeNull();
});

test('publishing emails an author who cannot add to ORCID that they can connect it', function (AuthorFactory $factory): void {
    $publication = acceptedReportForWorks();
    $publicationAuthor = reportAuthor($publication, $factory);

    publishReportForWorks($publication);

    Mail::assertQueued(WorkPublishedMail::class, 1);
    Mail::assertQueued(WorkPublishedMail::class, function (WorkPublishedMail $mail) use ($publicationAuthor): bool {
        $mail->assertSeeInHtml('connect your ORCID iD and allow the portal to update your record')
            ->assertSeeInHtml('connectez votre ORCID iD et autorisez le portail')
            ->assertDontSeeInHtml('You can now add it');

        return $mail->publicationAuthor->is($publicationAuthor);
    });
})->with([
    'not connected' => fn (): AuthorFactory => Author::factory(),
    'token expired' => fn (): AuthorFactory => Author::factory()->withOrcidConnection()->state(['orcid_expires_at' => now()->subDay()]),
]);

test('publishing offers nothing for works not to be offered', function (Closure $arrange, array $publishedWith): void {
    $mockClient = Saloon::fake([]);
    $publication = acceptedReportForWorks();
    [$publicationAuthor, $intent] = $arrange($publication);

    publishReportForWorks($publication, $publishedWith);

    $mockClient->assertNothingSent();
    Mail::assertNotQueued(WorkPublishedMail::class);
    expect($publicationAuthor->refresh()->orcid_sync)->toBe($intent);
})->with([
    'declined' => [fn (Publication $publication): array => [reportAuthor($publication, worksAutoSyncAuthor(), OrcidSyncIntent::DECLINED), OrcidSyncIntent::DECLINED], ['doi' => 'https://doi.org/10.60825/abc-123']],
    'no user account' => [fn (Publication $publication): array => [reportAuthor($publication, worksAutoSyncAuthor(), withUser: false), null], ['doi' => 'https://doi.org/10.60825/abc-123']],
    'no DOI' => [fn (Publication $publication): array => [reportAuthor($publication, worksAutoSyncAuthor()), null], []],
    'journal not flagged' => [function (Publication $publication): array {
        $publication->journal->update(['orcid_sync_enabled' => false]);

        return [reportAuthor($publication, worksAutoSyncAuthor()), null];
    }, ['doi' => 'https://doi.org/10.60825/abc-123']],
]);

test('works becoming eligible after publishing are not offered', function (): void {
    $mockClient = Saloon::fake([]);
    $publication = acceptedReportForWorks();
    $publicationAuthor = reportAuthor($publication, worksAutoSyncAuthor());
    publishReportForWorks($publication, []);

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/publications/{$publication->id}", ['published_on' => now()->toDateString(), 'doi' => 'https://doi.org/10.60825/abc-123'])
        ->assertOk();

    $mockClient->assertNothingSent();
    Mail::assertNotQueued(WorkPublishedMail::class);
    expect($publicationAuthor->refresh()->orcid_sync)->toBeNull();
});
