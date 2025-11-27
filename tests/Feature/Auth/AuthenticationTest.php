<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // assert attempt was logged:
        expect($user->authentications()->first()->login_successful)->toBeTrue();

        $this->assertAuthenticated();
        $response->assertNoContent();

    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // assert attempt was logged:
        expect($user->authentications()->first()->login_successful)->toBeFalse();

        $this->assertGuest();
    }

    public function test_a_user_with_a_null_password_cannot_login(): void
    {
        $user = User::factory()->create([
            'password' => null,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '',
        ]);

        $response->assertStatus(422);

        // assert empty password attempt was not logged:
        expect($user->authentications()->first())->toBeNull();

        $this->assertGuest();
    }

    public function test_an_invited_user_cannot_login_before_accepting_the_invitation_or_registering(): void
    {
        $user = User::factory()->invited()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'null',
        ]);

        $response->assertStatus(422);

        $this->assertGuest();
    }

    public function test_a_user_without_an_active_account_cannot_login(): void
    {
        $user = User::factory()->create([
            'active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(422);

        $this->assertGuest();
    }

    public function test_a_user_has_his_last_login_at_updated_on_successful_login(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertNull($user->previousSuccessfulLoginAt());

        // logout
        $this->post('/logout');

        // time travel 10 minutes in the future
        $this->travel(10)->minutes();

        // login again
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // get authenticated user route and see if it returns the last_login_at attribute
        $response = $this->get('/api/user');
        $response->assertJsonFragment(['last_login_at' => $user->previousSuccessfulLoginAt()]);

    }
}
