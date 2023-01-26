<?php

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

    $response = $this->actingAs($user)->get('api/user/orcid/verify');

    expect($response->status())->toBe(302);
    expect($response->headers->get('Location'))->toBe("https://orcid.org/oauth/authorize?client_id=$client_id&response_type=token&scope=/authenticate&redirect_uri=$encoded");
});
