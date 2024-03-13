<?php

use App\Enums\ORCID\ORCIDAuthScope;
use App\Models\User;

test('check OpenID redirect works', function () {
    $user = User::factory()->create();

    // use our own environment variables - these should match
    // the values in the phpunit.xml file
    $client_id = 'APP-XXXXXXXXXXXXXXXX';
    $redirect_uri = 'https://example.test/orcid/callback?';

    // check they match the values in the testing environment file
    expect(env('ORCID_CLIENT_ID'))->toBe($client_id);
    expect(env('ORCID_REDIRECT_URI'))->toBe($redirect_uri);

    $encoded = urlencode($redirect_uri);
    $scopes = ORCIDAuthScope::completeAccess();

    $response = $this->actingAs($user)->get('api/user/orcid/verify?locale=fr');

    expect($response->status())->toBe(302);
    expect($response->headers->get('Location'))->toBe("https://orcid.org/oauth/authorize?client_id=$client_id&response_type=code&scope=$scopes&redirect_uri=$encoded&lang=fr");
});

test('check front end redirect rule', function () {
    $response = $this->get('/api/orcid/redirect?code=1234');
    expect($response->status())->toBe(302);
    expect($response->headers->get('Location'))->toBe(config('app.frontend_url') . "#/auth/orcid-callback?code=1234");
});
