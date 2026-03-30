<?php

use App\Mail\ManagementReviewDueMail;
use App\Mail\ManagementReviewPendingMail;
use App\Mail\NotificationGroupMemberRemovedMail;
use App\Models\ManagementReviewStep;
use App\Models\NotificationGroupMember;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('a user can list their notification group members', function (): void {
    $user = User::factory()->create();
    NotificationGroupMember::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/user/notification-group-members')
        ->assertSuccessful();

    expect($response->json('data'))->toHaveCount(3);
});

test('a user can add a member to their notification group', function (): void {
    $user = User::factory()->create();
    $member = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/user/notification-group-members', [
        'member_user_id' => $member->id,
    ])->assertSuccessful();

    expect($response->json('data.member_user_id'))->toBe($member->id);
});

test('a user cannot add themselves to their notification group', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson('/api/user/notification-group-members', [
        'member_user_id' => $user->id,
    ])->assertUnprocessable();
});

test('a user can remove a member from their notification group', function (): void {
    $user = User::factory()->create();
    $membership = NotificationGroupMember::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->deleteJson("/api/user/notification-group-members/{$membership->id}")
        ->assertSuccessful();

    expect(NotificationGroupMember::query()->find($membership->id))->toBeNull();
});

test('a user can list groups they belong to', function (): void {
    $member = User::factory()->create();
    NotificationGroupMember::factory()->count(2)->create(['member_user_id' => $member->id]);

    $response = $this->actingAs($member)->getJson('/api/user/notification-group-memberships')
        ->assertSuccessful();

    expect($response->json('data'))->toHaveCount(2);
});

test('a member can remove themselves from a notification group', function (): void {
    Mail::fake();

    $owner = User::factory()->create();
    $member = User::factory()->create();
    $membership = NotificationGroupMember::factory()->create([
        'user_id' => $owner->id,
        'member_user_id' => $member->id,
    ]);

    $this->actingAs($member)->deleteJson("/api/user/notification-group-memberships/{$membership->id}")
        ->assertSuccessful();

    expect(NotificationGroupMember::query()->find($membership->id))->toBeNull();

    Mail::assertQueued(NotificationGroupMemberRemovedMail::class);
});

test('expired members are excluded from notification group emails', function (): void {
    $user = User::factory()->create();
    $activeMember = User::factory()->create();
    $expiredMember = User::factory()->create();

    NotificationGroupMember::factory()->create([
        'user_id' => $user->id,
        'member_user_id' => $activeMember->id,
    ]);

    NotificationGroupMember::factory()->expired()->create([
        'user_id' => $user->id,
        'member_user_id' => $expiredMember->id,
    ]);

    $emails = $user->getNotificationGroupEmails();

    expect($emails)->toContain($activeMember->email);
    expect($emails)->not->toContain($expiredMember->email);
});

test('ManagementReviewDueMail includes notification group CCs', function (): void {
    $user = User::factory()->create();
    $ccMember = User::factory()->create();

    NotificationGroupMember::factory()->create([
        'user_id' => $user->id,
        'member_user_id' => $ccMember->id,
    ]);

    $reviews = ManagementReviewStep::factory()->count(1)->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->addDay(),
    ]);

    $mail = new ManagementReviewDueMail($reviews, $user);
    $mail->build();

    $ccAddresses = collect($mail->cc)->pluck('address');
    expect($ccAddresses)->toContain($ccMember->email);
});

test('ManagementReviewPendingMail includes notification group CCs', function (): void {
    $user = User::factory()->create();
    $ccMember = User::factory()->create();

    NotificationGroupMember::factory()->create([
        'user_id' => $user->id,
        'member_user_id' => $ccMember->id,
    ]);

    $reviews = ManagementReviewStep::factory()->count(1)->create([
        'user_id' => $user->id,
    ]);

    $mail = new ManagementReviewPendingMail($reviews, $user);
    $mail->build();

    $ccAddresses = collect($mail->cc)->pluck('address');
    expect($ccAddresses)->toContain($ccMember->email);
});

test('notification group member activity is logged', function (): void {
    $user = User::factory()->create();
    $member = User::factory()->create();

    $this->actingAs($user)->postJson('/api/user/notification-group-members', [
        'member_user_id' => $member->id,
    ])->assertSuccessful();

    $groupMember = NotificationGroupMember::query()->first();
    expect($groupMember->activitiesAsSubject)->not->toBeEmpty();
});
