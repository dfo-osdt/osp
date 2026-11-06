<?php

use App\Enums\ManuscriptRecordType;
use App\Enums\Permissions\UserRole;
use App\Enums\PublicationStatus;
use App\Mail\ReviewContributionPublishedMail;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use App\Models\Publication;
use App\Models\User;
use Database\Factories\AuthorFactory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

beforeEach(function (): void {
    Mail::fake();
    Queue::fake();
});

/**
 * An accepted report in a flagged DFO series, on a secondary manuscript
 */
function acceptedReportForMail(): Publication
{
    return Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'accepted_on' => now()->subMonth(),
        'doi' => null,
        'manuscript_record_id' => ManuscriptRecord::factory()->create(['type' => ManuscriptRecordType::SECONDARY])->id,
        'journal_id' => Journal::factory()->dfoSeries()->create(['issn' => '1919-5079', 'orcid_sync_enabled' => true])->id,
    ]);
}

/**
 * A review contribution to the publication by the given author
 */
function contributionByAuthor(Publication $publication, Author $author): ManuscriptReviewContributor
{
    return ManuscriptReviewContributor::factory()->create([
        'manuscript_record_id' => $publication->manuscript_record_id,
        'author_id' => $author->id,
    ]);
}

/**
 * An author with a user account, from the given author factory
 */
function authorWithUser(AuthorFactory $factory): Author
{
    $author = $factory->create();
    User::factory()->create(['email' => $author->email]);

    return $author->refresh();
}

/**
 * Publish the publication as a chief editor, who publishes DFO reports
 *
 * @param  array<string, mixed>  $attributes
 */
function publishReportForMail(Publication $publication, array $attributes = ['doi' => 'https://doi.org/10.60825/abc-123']): void
{
    test()->actingAs(User::factory()->withRoles([UserRole::CHIEF_EDITOR])->create())
        ->putJson("api/publications/{$publication->id}", [
            'status' => PublicationStatus::PUBLISHED->value,
            'published_on' => now()->toDateString(),
            ...$attributes,
        ])
        ->assertOk();
}

test('publishing emails an author connected to ORCID that they can add their contribution', function (): void {
    $publication = acceptedReportForMail();
    $author = authorWithUser(Author::factory()->withOrcidConnection());
    $contributor = contributionByAuthor($publication, $author);

    publishReportForMail($publication);

    Mail::assertQueuedCount(1);
    Mail::assertQueued(ReviewContributionPublishedMail::class, function (ReviewContributionPublishedMail $mail) use ($author, $contributor): bool {
        $mail->assertSeeInHtml('You can now get credit for it by adding it to your ORCID record')
            ->assertSeeInHtml('Vous pouvez maintenant en obtenir la reconnaissance')
            ->assertDontSeeInHtml('connect your ORCID iD')
            ->assertSeeInHtml('redirect=%2Fsettings%2Fauthor%2Forcid-reviews');

        return $mail->hasTo($author->user->email) && $mail->contributor->is($contributor);
    });
});

test('publishing emails an author who cannot add to ORCID that they can connect it to get credit', function (AuthorFactory $factory): void {
    $publication = acceptedReportForMail();
    $author = authorWithUser($factory);
    $contributor = contributionByAuthor($publication, $author);

    publishReportForMail($publication);

    Mail::assertQueuedCount(1);
    Mail::assertQueued(ReviewContributionPublishedMail::class, function (ReviewContributionPublishedMail $mail) use ($author, $contributor): bool {
        $mail->assertSeeInHtml('connect your ORCID iD and allow the portal to update your record')
            ->assertSeeInHtml('connectez votre ORCID iD et autorisez le portail')
            ->assertDontSeeInHtml('You can now get credit')
            ->assertSeeInHtml('redirect=%2Fsettings%2Fauthor%2Forcid-reviews');

        return $mail->hasTo($author->user->email) && $mail->contributor->is($contributor);
    });
})->with([
    'not connected' => fn (): AuthorFactory => Author::factory(),
    'token expired' => fn (): AuthorFactory => Author::factory()->withOrcidConnection()->state(['orcid_expires_at' => now()->subDay()]),
    'no write scope' => fn (): AuthorFactory => Author::factory()->withOrcidConnection('/read-limited'),
]);

test('the login link keeps a plus address intact', function (): void {
    $publication = acceptedReportForMail();
    contributionByAuthor($publication, authorWithUser(Author::factory()->withOrcidConnection()->state(['email' => 'jane+orcid@dfo-mpo.gc.ca'])));

    publishReportForMail($publication);

    Mail::assertQueued(ReviewContributionPublishedMail::class, function (ReviewContributionPublishedMail $mail): bool {
        $mail->assertSeeInHtml('email=jane%2Borcid%40dfo-mpo.gc.ca');

        return true;
    });
});

test('publishing emails each contributor without auto sync once, alongside those added automatically', function (): void {
    $publication = acceptedReportForMail();
    $emailedAuthors = collect([
        authorWithUser(Author::factory()->withOrcidConnection()),
        authorWithUser(Author::factory()),
    ])->each(fn (Author $author): ManuscriptReviewContributor => contributionByAuthor($publication, $author));
    contributionByAuthor($publication, authorWithUser(Author::factory()->withOrcidConnection()->state(['orcid_auto_sync_reviews' => true])));

    publishReportForMail($publication);

    Mail::assertQueuedCount(2);
    $emailedAuthors->each(fn (Author $author) => Mail::assertQueued(ReviewContributionPublishedMail::class, fn (ReviewContributionPublishedMail $mail): bool => $mail->hasTo($author->user->email)));
});

test('publishing does not email authors whose contribution is not to be offered', function (Closure $arrange, array $publishedWith): void {
    $publication = acceptedReportForMail();
    $arrange($publication);

    publishReportForMail($publication, $publishedWith);

    Mail::assertNothingQueued();
})->with([
    'no user account' => [fn (Publication $publication): ManuscriptReviewContributor => contributionByAuthor($publication, Author::factory()->withOrcidConnection()->create()), ['doi' => 'https://doi.org/10.60825/abc-123']],
    'auto sync on' => [fn (Publication $publication): ManuscriptReviewContributor => contributionByAuthor($publication, authorWithUser(Author::factory()->withOrcidConnection()->state(['orcid_auto_sync_reviews' => true]))), ['doi' => 'https://doi.org/10.60825/abc-123']],
    'no DOI' => [fn (Publication $publication): ManuscriptReviewContributor => contributionByAuthor($publication, authorWithUser(Author::factory()->withOrcidConnection())), []],
    'journal not flagged' => [function (Publication $publication): void {
        $publication->journal->update(['orcid_sync_enabled' => false]);
        contributionByAuthor($publication, authorWithUser(Author::factory()->withOrcidConnection()));
    }, ['doi' => 'https://doi.org/10.60825/abc-123']],
]);

test('contributions becoming eligible after publishing are not emailed about', function (): void {
    $publication = acceptedReportForMail();
    contributionByAuthor($publication, authorWithUser(Author::factory()->withOrcidConnection()));
    publishReportForMail($publication, []);

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/publications/{$publication->id}", ['published_on' => now()->toDateString(), 'doi' => 'https://doi.org/10.60825/abc-123'])
        ->assertOk();

    Mail::assertNothingQueued();
});
