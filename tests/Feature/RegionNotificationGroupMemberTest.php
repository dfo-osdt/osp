<?php

use App\Actions\CreatePublicationFromManuscript;
use App\Enums\ManuscriptRecordType;
use App\Events\PublicationAccepted;
use App\Mail\ManuscriptManagementReviewComplete;
use App\Mail\PublicationAcceptedMail;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\NotificationGroupMember;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\Region;
use App\Models\RegionNotificationGroupMember;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('a region notification group includes each member\'s individual notification group', function (): void {
    $region = Region::query()
        ->whereDoesntHave('notificationGroupMembers')
        ->first();
    $groupMember = User::factory()->create();
    $forwardedMember = User::factory()->create();

    RegionNotificationGroupMember::factory()->create([
        'region_id' => $region->id,
        'user_id' => $groupMember->id,
    ]);

    NotificationGroupMember::factory()->create([
        'user_id' => $groupMember->id,
        'member_user_id' => $forwardedMember->id,
    ]);

    $emails = $region->getNotificationGroupEmails();

    expect($emails)->toContain($groupMember->email);
    expect($emails)->toContain($forwardedMember->email);
});

test('ManuscriptManagementReviewComplete cc\'s the regional notification group for third-party manuscripts', function (): void {
    $region = Region::query()->first();
    $groupMember = User::factory()->create();

    RegionNotificationGroupMember::factory()->create([
        'region_id' => $region->id,
        'user_id' => $groupMember->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->create([
        'type' => ManuscriptRecordType::PRIMARY,
        'region_id' => $region->id,
    ]);

    $mail = new ManuscriptManagementReviewComplete($manuscript);
    $mail->build();

    $ccAddresses = collect($mail->cc)->pluck('address');
    expect($ccAddresses)->toContain($groupMember->email);
});

test('ManuscriptManagementReviewComplete does not cc the regional notification group for secondary manuscripts', function (): void {
    $region = Region::query()->first();
    $groupMember = User::factory()->create();

    RegionNotificationGroupMember::factory()->create([
        'region_id' => $region->id,
        'user_id' => $groupMember->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->create([
        'type' => ManuscriptRecordType::SECONDARY,
        'region_id' => $region->id,
    ]);

    $mail = new ManuscriptManagementReviewComplete($manuscript);
    $mail->build();

    $ccAddresses = collect($mail->cc)->pluck('address');
    expect($ccAddresses)->not->toContain($groupMember->email);
});

test('the PublicationAccepted event notifies the regional notification group', function (): void {
    Mail::fake();

    $region = Region::query()->first();
    $groupMember = User::factory()->isFromAuthorizedDomain()->create();

    RegionNotificationGroupMember::factory()->create([
        'region_id' => $region->id,
        'user_id' => $groupMember->id,
    ]);

    $publication = Publication::factory()->create([
        'region_id' => $region->id,
    ]);

    event(new PublicationAccepted($publication));

    Mail::assertQueued(PublicationAcceptedMail::class, fn ($mail): bool => $mail->publication->is($publication)
        && $mail->recipientEmails()->contains($groupMember->email));
});

test('the PublicationAccepted event notifies the manuscript\'s managers', function (): void {
    Mail::fake();

    $region = Region::query()->first();
    $manager = User::factory()->isFromAuthorizedDomain()->create();

    $manuscript = ManuscriptRecord::factory()->create(['region_id' => $region->id]);
    ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $manager->id,
    ]);

    $publication = Publication::factory()->create([
        'region_id' => $region->id,
        'manuscript_record_id' => $manuscript->id,
    ]);

    event(new PublicationAccepted($publication));

    Mail::assertQueued(PublicationAcceptedMail::class, fn ($mail): bool => $mail->publication->is($publication)
        && $mail->recipientEmails()->contains($manager->email));
});

test('the PublicationAccepted event notifies the publication\'s authors', function (): void {
    Mail::fake();

    $region = Region::query()->first();
    $author = Author::factory()->isFromAuthorizedDomain()->create();

    $publication = Publication::factory()->create([
        'region_id' => $region->id,
    ]);
    PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => $author->id,
    ]);

    event(new PublicationAccepted($publication));

    Mail::assertQueued(PublicationAcceptedMail::class, fn ($mail): bool => $mail->publication->is($publication)
        && $mail->recipientEmails()->contains($author->email));
});

test('the PublicationAccepted event notifies the publication owner', function (): void {
    Mail::fake();

    $region = Region::query()->first();
    $owner = User::factory()->isFromAuthorizedDomain()->create();

    $publication = Publication::factory()->create([
        'region_id' => $region->id,
        'user_id' => $owner->id,
    ]);

    event(new PublicationAccepted($publication));

    Mail::assertQueued(PublicationAcceptedMail::class, fn ($mail): bool => $mail->publication->is($publication)
        && $mail->recipientEmails()->contains($owner->email));
});

test('the PublicationAccepted event notifies the publication owner notification group', function (): void {
    Mail::fake();

    $region = Region::query()->first();
    $owner = User::factory()->isFromAuthorizedDomain()->create();
    $notificationGroupMember = User::factory()->isFromAuthorizedDomain()->create();

    NotificationGroupMember::factory()->create([
        'user_id' => $owner->id,
        'member_user_id' => $notificationGroupMember->id,
    ]);

    $publication = Publication::factory()->create([
        'region_id' => $region->id,
        'user_id' => $owner->id,
    ]);

    event(new PublicationAccepted($publication));

    Mail::assertQueued(PublicationAcceptedMail::class, fn ($mail): bool => $mail->publication->is($publication)
        && $mail->recipientEmails()->contains($notificationGroupMember->email));
});

test('the PublicationAccepted event notifies manuscript manager notification groups', function (): void {
    Mail::fake();

    $region = Region::query()->first();
    $manager = User::factory()->isFromAuthorizedDomain()->create();
    $notificationGroupMember = User::factory()->isFromAuthorizedDomain()->create();

    NotificationGroupMember::factory()->create([
        'user_id' => $manager->id,
        'member_user_id' => $notificationGroupMember->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->create(['region_id' => $region->id]);
    ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $manager->id,
    ]);

    $publication = Publication::factory()->create([
        'region_id' => $region->id,
        'manuscript_record_id' => $manuscript->id,
    ]);

    event(new PublicationAccepted($publication));

    Mail::assertQueued(PublicationAcceptedMail::class, fn ($mail): bool => $mail->publication->is($publication)
        && $mail->recipientEmails()->contains($notificationGroupMember->email));
});

test('the PublicationAccepted event notifies publication author user notification groups', function (): void {
    Mail::fake();

    $region = Region::query()->first();
    $authorUser = User::factory()->isFromAuthorizedDomain()->create();
    $notificationGroupMember = User::factory()->isFromAuthorizedDomain()->create();

    NotificationGroupMember::factory()->create([
        'user_id' => $authorUser->id,
        'member_user_id' => $notificationGroupMember->id,
    ]);

    $author = Author::factory()->isFromAuthorizedDomain()->create([
        'user_id' => $authorUser->id,
    ]);

    $publication = Publication::factory()->create([
        'region_id' => $region->id,
    ]);
    PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => $author->id,
    ]);

    event(new PublicationAccepted($publication));

    Mail::assertQueued(PublicationAcceptedMail::class, fn ($mail): bool => $mail->publication->is($publication)
        && $mail->recipientEmails()->contains($notificationGroupMember->email));
});

test('the PublicationAccepted event does not send mail when there are no recipients', function (): void {
    $region = Region::query()
        ->whereDoesntHave('notificationGroupMembers')
        ->first();
    $owner = User::factory()->create([
        'email' => 'owner@external.test',
    ]);

    $publication = Publication::factory()->create([
        'region_id' => $region->id,
        'user_id' => $owner->id,
    ]);

    expect((new PublicationAcceptedMail($publication))->recipientEmails())->toBeEmpty();
});

test('creating a publication via the API does not queue an acceptance notification before authors are added', function (): void {
    Mail::fake();

    $region = Region::query()->first();
    $groupMember = User::factory()->isFromAuthorizedDomain()->create();

    RegionNotificationGroupMember::factory()->create([
        'region_id' => $region->id,
        'user_id' => $groupMember->id,
    ]);

    $user = User::factory()->create();
    $journal = Journal::factory()->create();

    $this->actingAs($user)->postJson('/api/publications', [
        'status' => 'accepted',
        'title' => 'Test Publication',
        'journal_id' => $journal->id,
        'accepted_on' => '2021-01-01',
        'region_id' => $region->id,
    ])->assertSuccessful();

    Mail::assertNothingQueued();
});

test('creating a publication from a manuscript queues an acceptance notification', function (): void {
    Mail::fake();

    $region = Region::query()->first();
    $groupMember = User::factory()->isFromAuthorizedDomain()->create();

    RegionNotificationGroupMember::factory()->create([
        'region_id' => $region->id,
        'user_id' => $groupMember->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->accepted()->create([
        'region_id' => $region->id,
    ]);
    $journal = Journal::factory()->create();

    $publication = CreatePublicationFromManuscript::handle($manuscript, $journal);

    Mail::assertQueued(PublicationAcceptedMail::class, fn ($mail): bool => $mail->publication->is($publication)
        && $mail->recipientEmails()->contains($groupMember->email));
});
