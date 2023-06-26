<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\Authentication\PasswordResetNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->postJson('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, PasswordResetNotification::class);
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Notification::fake();

        $user = User::factory()->create(['new_password_required' => true]);

        $this->postJson('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, PasswordResetNotification::class, function ($notification) use ($user) {
            $password = Str::random(16);

            $response = $this->postJson('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => $password,
                'password_confirmation' => $password,
            ]);

            $response->assertOk();

            return true;
        });

        $user->refresh();
        $this->assertFalse($user->new_password_required);
    }

    public function test_password_cant_be_reset_with_valid_token_and_poor_password()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->postJson('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, PasswordResetNotification::class, function ($notification) use ($user) {
            $response = $this->postJson('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'passwordpassword',
                'password_confirmation' => 'passwordpassword',
            ]);

            $response->assertStatus(422);

            return true;
        });
    }
}
