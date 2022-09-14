<?php

use App\Models\Author;
use App\Models\Organization;
use App\Models\User;

test('a user can get list of authors', function () {
    $this->seed();

    Author::factory()->count(15)->create();

    $user = User::factory()->create();

    // only when user is logged in
    $response = $this->getJson('api/authors')->assertUnauthorized();

    $response = $this->actingAs($user)->getJson('api/authors?limit=5&include=organization')->assertOk();

    expect($response->json('data'))->toHaveCount(5);
    expect($response->json('meta.total'))->toBe(15);

    ray($response->json());
});

test('a user can create an author', function () {
    $this->seed();

    $user = User::factory()->create();

    $minimumData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'John.Doe@dfo-mpo.gc.ca',
        'orcid' => '',
    ];

    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);

    // email to lowercase in data
    $minimumData['email'] = strtolower($minimumData['email']);

    $response->assertCreated()->assertJson([
        'data' => $minimumData,
    ]);
});

test('a user can create an author with an ORCID', function () {
    $this->seed();

    $user = User::factory()->create();

    $invalidFormatOrcid = '0000-0002-X868-2722';
    $badChecksumOrcid = '0000-0002-0868-2725';
    $validOrcid = '0000-0002-0868-2726';

    $minimumData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'john.doe@dfo-mpo.gc.ca',
        'orcid' => $invalidFormatOrcid,
    ];

    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertStatus(422)->assertJsonValidationErrors('orcid');
    expect($response->json('errors.orcid.0'))->toContain('format');

    $minimumData['orcid'] = $badChecksumOrcid;
    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertStatus(422)->assertJsonValidationErrors('orcid');
    expect($response->json('errors.orcid.0'))->toContain('checksum');

    $minimumData['orcid'] = $validOrcid;
    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertCreated()->assertJson([
        'data' => $minimumData,
    ]);
});

test('a user cannot create an author if email already exist', function () {
    $this->seed();

    $user = User::factory()->create();

    $author = Author::factory()->create();

    $minimumData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => $author->email,
        'orcid' => '',
    ];

    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);

    $response->assertStatus(422)->assertJsonValidationErrors('email');
});

test('a user cannot create an author if orcid already exists', function () {
    $this->seed();

    $user = User::factory()->create();

    $author = Author::factory()->create([
        'orcid' => '0000-0002-0868-2726',
    ]);

    $minimumData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'john.doe@dfo-mpo.gc.ca',
        'orcid' => $author->orcid,
    ];

    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertStatus(422)->assertJsonValidationErrors('orcid');
});
