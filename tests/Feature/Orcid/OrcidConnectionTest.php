<?php

use App\Jobs\SyncAuthorEmploymentWithOrcid;
use App\Models\AuthorEmployment;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use VincentAuger\OrcidSdk\Requests\OAuth\ExchangeAuthorizationCode;
use VincentAuger\OrcidSdk\Requests\OAuth\RevokeToken;

/**
 * Token response as returned by ORCID for the authorization code flow
 */
function orcidTokenResponse(): MockResponse
{
    return MockResponse::make([
        'access_token' => 'new-access-token',
        'token_type' => 'bearer',
        'refresh_token' => 'new-refresh-token',
        'expires_in' => 631138518,
        'scope' => '/read-limited /activities/update /person/update',
        'name' => 'Josiah Carberry',
        'orcid' => '0000-0002-1825-0097',
    ]);
}

test('the callback stores the token and syncs pending employments', function (): void {
    Queue::fake();
    $mockClient = Saloon::fake([ExchangeAuthorizationCode::class => orcidTokenResponse()]);

    $user = User::factory()->create();
    $pending = AuthorEmployment::factory()->create(['author_id' => $user->author->id, 'organization_id' => Organization::factory()]);
    Cache::put('abcKEY123', $user->id, now()->addMinutes(15));

    $this->get('api/orcid/callback?code=Q70Y3A&key=abcKEY123')
        ->assertRedirect(config('app.frontend_url').'#/auth/orcid-callback?status=success');

    $author = $user->author->refresh();
    expect($author->orcid)->toBe('https://orcid.org/0000-0002-1825-0097')
        ->and($author->orcid_access_token)->toBe('new-access-token')
        ->and($author->orcid_refresh_token)->toBe('new-refresh-token')
        ->and($author->orcid_token_scope)->toBe('/read-limited /activities/update /person/update')
        ->and($author->orcid_verified)->toBeTrue()
        ->and($author->hasValidOrcidCredentials())->toBeTrue();

    // the code is exchanged with the exact redirect URI used to authorize
    $mockClient->assertSent(fn (ExchangeAuthorizationCode $request): bool => $request->body()->get('code') === 'Q70Y3A'
        && $request->body()->get('redirect_uri') === config('osp.orcid.redirect_uri').'?key=abcKEY123');

    Queue::assertPushed(SyncAuthorEmploymentWithOrcid::class, fn (SyncAuthorEmploymentWithOrcid $job): bool => $job->authorEmployment->is($pending));
});

test('the callback reports a failed token exchange', function (): void {
    Queue::fake();
    Saloon::fake([ExchangeAuthorizationCode::class => MockResponse::make(['error' => 'invalid_grant', 'error_description' => 'Invalid authorization code'], 400)]);

    $user = User::factory()->create();
    Cache::put('abcKEY123', $user->id, now()->addMinutes(15));

    $this->get('api/orcid/callback?code=Q70Y3A&key=abcKEY123')
        ->assertRedirect(config('app.frontend_url').'#/auth/orcid-callback?status=failed');

    expect($user->author->refresh()->orcid_verified)->not->toBeTrue();
    Queue::assertNothingPushed();
});

test('the callback rejects an unknown key without calling ORCID', function (): void {
    $mockClient = Saloon::fake([]);

    $this->get('api/orcid/callback?code=Q70Y3A&key=unknownKEY')
        ->assertRedirect(config('app.frontend_url').'#/auth/orcid-callback?status=invalid_key');

    $mockClient->assertNothingSent();
});

test('revoking the token disconnects the author', function (): void {
    $mockClient = Saloon::fake([RevokeToken::class => MockResponse::make('', 200)]);
    $user = User::factory()->create();
    $user->author->forceFill(['orcid_verified' => true, 'orcid_access_token' => 'token-to-revoke'])->save();

    $this->actingAs($user)->postJson('api/user/orcid/revoke')->assertOk();

    $mockClient->assertSent(fn (RevokeToken $request): bool => $request->body()->get('token') === 'token-to-revoke');
    expect($user->author->refresh()->orcid_access_token)->toBeNull()
        ->and($user->author->orcid_verified)->toBeFalse();
});

test('a failed revocation keeps the connection', function (): void {
    Saloon::fake([RevokeToken::class => MockResponse::make(['error' => 'server_error'], 500)]);
    $user = User::factory()->create();
    $user->author->forceFill(['orcid_verified' => true, 'orcid_access_token' => 'token-to-revoke'])->save();

    $this->actingAs($user)->postJson('api/user/orcid/revoke')->assertUnprocessable();

    expect($user->author->refresh()->orcid_access_token)->toBe('token-to-revoke');
});
