<?php

namespace Tests\Feature\Auth;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Notification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_can_be_verified()
    {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $response = $this->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $user->refresh();

        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertNull($user->email_verification_token);
        $this->assertTrue($user->active);
        $response->assertRedirect(config('app.frontend_url').'#/auth/login?verified=1'.'&email='.$user->email);
    }

    public function test_email_can_be_verified_for_user_with_invitation()
    {
        $user = User::factory()->unverified()->create();
        $user->invitation()->create([
            'invitation_token' => Invitation::generateInvitationToken(),
            'invited_by' => User::factory()->create()->id,
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $response = $this->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $user->refresh();

        $this->assertNotNull($user->invitation->registered_at);
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertNull($user->email_verification_token);
        $this->assertTrue($user->active);
        $response->assertRedirect(config('app.frontend_url').'#/auth/login?verified=1'.'&email='.$user->email);
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $response = $this->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(config('app.frontend_url').'#/invalid-signature');
    }

    public function test_verification_email_can_be_sent_again()
    {
        $user = User::factory()->unverified()->create();

        Notification::fake();

        $response = $this->postJson('/email/verification-notification', [
            'email' => $user->email,
        ]);

        $response->assertJson(['status' => 'verification-link-sent']);
        Notification::assertSentToTimes($user, \Illuminate\Auth\Notifications\VerifyEmail::class, 1);
    }

    public function test_verification_email_wont_be_sent_if_already_verified()
    {
        $user = User::factory()->create();

        Notification::fake();

        $response = $this->postJson('/email/verification-notification', [
            'email' => $user->email,
        ]);

        $response->assertJson(['status' => 'already-verified']);
        Notification::assertNothingSent();
    }

    public function test_verification_email_wont_be_sent_if_user_doesnt_exist()
    {
        Notification::fake();

        $response = $this->postJson('/email/verification-notification', [
            'email' => 'nobody@nobody.com',
        ]);

        ray($response->json());
        $response->assertJson(['message' => 'The selected email is invalid.']);
        Notification::assertNothingSent();
    }
}
