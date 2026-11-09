<?php

use App\Enums\Permissions\UserRole;
use App\Enums\PublicationStatus;
use App\Mail\PublicationPublishedMail;
use App\Models\Author;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\Region;
use App\Models\RegionNotificationGroupMember;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
});

/**
 * An accepted publication from a manuscript, in a third-party journal
 *
 * @param  array<string, mixed>  $attributes
 */
function acceptedPublication(array $attributes = []): Publication
{
    return Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'accepted_on' => now()->subMonth(),
        'region_id' => Region::query()->first()->id,
        'manuscript_record_id' => ManuscriptRecord::factory()->create()->id,
        ...$attributes,
    ]);
}

/**
 * Publish the publication as the given user
 *
 * @param  array<string, mixed>  $attributes
 */
function publishAs(User $user, Publication $publication, array $attributes = []): void
{
    test()->actingAs($user)
        ->putJson("api/publications/{$publication->id}", [
            'status' => PublicationStatus::PUBLISHED->value,
            'published_on' => now()->toDateString(),
            ...$attributes,
        ])
        ->assertOk();
}

test('a chief editor publishing a publication notifies its authors, managers and region', function (): void {
    $publication = acceptedPublication();

    $authorUser = User::factory()->isFromAuthorizedDomain()->create();
    $coAuthor = Author::factory()->isFromAuthorizedDomain()->create(['user_id' => $authorUser->id]);
    $authorWithoutAccount = Author::factory()->isFromAuthorizedDomain()->create();
    foreach ([$coAuthor, $authorWithoutAccount] as $author) {
        PublicationAuthor::factory()->create([
            'publication_id' => $publication->id,
            'author_id' => $author->id,
        ]);
    }

    $manager = User::factory()->isFromAuthorizedDomain()->create();
    ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $publication->manuscript_record_id,
        'user_id' => $manager->id,
    ]);

    $regionGroupMember = User::factory()->isFromAuthorizedDomain()->create();
    RegionNotificationGroupMember::factory()->create([
        'region_id' => $publication->region_id,
        'user_id' => $regionGroupMember->id,
    ]);

    $chiefEditor = User::factory()->withRoles([UserRole::CHIEF_EDITOR])->create();

    publishAs($chiefEditor, $publication, ['doi' => 'https://doi.org/10.60825/abc-123']);

    Mail::assertQueued(PublicationPublishedMail::class, function (PublicationPublishedMail $mail) use ($publication, $chiefEditor, $authorUser, $authorWithoutAccount, $manager, $regionGroupMember): bool {
        $mail->assertSeeInHtml($chiefEditor->full_name)
            ->assertSeeInHtml('https://doi.org/10.60825/abc-123');

        return $mail->publication->is($publication)
            && $mail->hasTo($authorUser->email)
            && $mail->hasTo($authorWithoutAccount->email)
            && $mail->hasTo($manager->email)
            && $mail->hasTo($regionGroupMember->email);
    });
});

test('the notification for a secondary publication names Science Publications rather than the chief editor', function (): void {
    $publication = acceptedPublication([
        'manuscript_record_id' => ManuscriptRecord::factory()->secondary()->create()->id,
    ]);
    $coAuthor = Author::factory()->isFromAuthorizedDomain()->create();
    PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => $coAuthor->id,
    ]);
    $chiefEditor = User::factory()->withRoles([UserRole::CHIEF_EDITOR])->create();

    publishAs($chiefEditor, $publication);

    Mail::assertQueued(PublicationPublishedMail::class, function (PublicationPublishedMail $mail) use ($chiefEditor, $coAuthor): bool {
        $mail->assertSeeInHtml('Science Publications')
            ->assertDontSeeInHtml($chiefEditor->full_name);

        return $mail->hasTo($coAuthor->email);
    });
});

test('an author publishing their own publication still notifies the other authors', function (): void {
    $owner = User::factory()->isFromAuthorizedDomain()->create();
    $publication = acceptedPublication(['user_id' => $owner->id]);
    $coAuthor = Author::factory()->isFromAuthorizedDomain()->create();
    PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => $coAuthor->id,
    ]);

    publishAs($owner, $publication);

    Mail::assertQueued(PublicationPublishedMail::class, fn (PublicationPublishedMail $mail): bool => $mail->hasTo($coAuthor->email));
});

test('publishing a publication without a manuscript record sends no notification', function (): void {
    $owner = User::factory()->isFromAuthorizedDomain()->create();
    $publication = acceptedPublication(['user_id' => $owner->id, 'manuscript_record_id' => null]);

    publishAs($owner, $publication);

    Mail::assertNotQueued(PublicationPublishedMail::class);
});

test('saving an already published publication sends no notification', function (): void {
    $owner = User::factory()->isFromAuthorizedDomain()->create();
    $publication = acceptedPublication([
        'user_id' => $owner->id,
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => now()->subWeek(),
    ]);

    publishAs($owner, $publication, ['title' => 'A new title']);

    Mail::assertNotQueued(PublicationPublishedMail::class);
});
