<?php

use App\Models\User;

test('check OpenID redirect works', function (): void {
    $user = User::factory()->create();

    // use our own environment variables - these should match
    // the values in the phpunit.xml file
    $client_id = 'APP-XXXXXXXXXXXXXXXX';
    $redirect_uri = 'https://example.test/orcid/callback?';

    // check they match the values in the testing environment file
    expect(env('ORCID_CLIENT_ID'))->toBe($client_id);
    expect(env('ORCID_REDIRECT_URI'))->toBe($redirect_uri);

    $encoded = rawurlencode($redirect_uri);
    $scopes = rawurlencode('/read-limited /activities/update /person/update');

    $response = $this->actingAs($user)->get('api/user/orcid/verify?locale=fr');

    expect($response->status())->toBe(302);
    expect($response->headers->get('Location'))->toStartWith("https://orcid.org/oauth/authorize?client_id=$client_id&response_type=code&scope=$scopes&redirect_uri=$encoded")->toEndWith('&lang=fr');

});
