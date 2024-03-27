<?php

use App\Models\Author;
use App\Models\Organization;
use App\Models\User;

test('a user can get list of authors', function () {
    Author::factory()->count(15)->create();

    $user = User::factory()->create();

    // only when user is logged in
    $response = $this->getJson('api/authors')->assertUnauthorized();

    $response = $this->actingAs($user)->getJson('api/authors?limit=5&include=organization')->assertOk();

    expect($response->json('data'))->toHaveCount(5);
    expect($response->json('meta.total'))->toBe(Author::count());
});

test('a user can create an author', function () {
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
    $user = User::factory()->create();

    $invalidFormatOrcid = 'https://orcid.org/0000-0002-X868-2722';
    $badChecksumOrcid = 'https://orcid.org/0000-0002-0868-2725';
    $badUrlPrefix = 'https://orci.org/0000-0002-0868-2726';
    $sandboxUrlPrefix = 'https://sandbox.orcid.org/0000-0002-0868-2726';
    $validOrcid = 'https://orcid.org/0000-0002-0868-2726';

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

    $minimumData['orcid'] = $badUrlPrefix;
    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertStatus(422)->assertJsonValidationErrors('orcid');
    expect($response->json('errors.orcid.0'))->toContain('prefix');

    $minimumData['orcid'] = $sandboxUrlPrefix;
    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertCreated()->assertJson([
        'data' => $minimumData,
    ]);

    $minimumData['orcid'] = $validOrcid;
    $minimumData['email'] = 'john.doe2@test.local';
    $response = $this->actingAs($user)->postJson('api/authors', $minimumData);
    $response->assertCreated()->assertJson([
        'data' => $minimumData,
    ]);
});

test('a user cannot create an author if email already exist', function () {
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
    $user = User::factory()->create();

    $author = Author::factory()->create([
        'orcid' => 'https://orcid.org/0000-0002-0868-2726',
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

test('a user can get an author profile', function () {
    $user = User::factory()->create();

    $author = Author::factory()->create();

    $response = $this->actingAs($user)->getJson('api/authors/' . $author->id);

    $response->assertOk();
    expect($response->json('data'))->toHaveKey('id', $author->id);
});

test('a user can update an author profile without an owner', function () {
    $user = User::factory()->create();

    $author = Author::factory()->create();

    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'kewler.superw@dfo-mpo.gc.ca',
        'orcid' => 'https://orcid.org/0000-0002-0868-2726',
    ];

    $response = $this->actingAs($user)->putJson('api/authors/' . $author->id, $data)->assertOk();

    $response->assertJson([
        'data' => $data,
    ]);
});

test('a user can update their own author profile', function () {
    $user = User::factory()->create();

    $author = $user->author;

    $data = [
        'first_name' => 'Lala',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'kewler.superw@dfo-mpo.gc.ca',
        'orcid' => 'https://orcid.org/0000-0002-0868-2726',
    ];

    $response = $this->actingAs($user)->putJson('api/authors/' . $author->id, $data)->assertOk();

    // check that first name, last name, and email are not updated here
    $data['first_name'] = $author->first_name;
    $data['last_name'] = $author->last_name;
    $data['email'] = $author->email;

    $response->assertJson([
        'data' => $data,
    ]);
});

test('a user cannot edit an author profile that is owned by another user', function () {
    $user = User::factory()->create();

    $author = User::factory()->create()->author;

    $data = [
        'first_name' => 'Lala',
        'last_name' => 'Doe',
        'organization_id' => Organization::factory()->create()->id,
        'email' => 'kewler.superw@dfo-mpo.gc.ca',
        'orcid' => 'https://orcid.org/0000-0002-0868-2726',
    ];

    $response = $this->actingAs($user)->putJson('api/authors/' . $author->id, $data)->assertForbidden();
});
