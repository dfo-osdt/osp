<?php

use App\Enums\Permissions\UserRole;
use App\Models\User;

test('an authenticated user can search through the users', function (): void {
    User::factory()->count(20)->create();

    $user = User::query()->find(1);

    $this->getJSON('api/users?limit=10')->assertUnauthorized();

    $response = $this->actingAs($user)->getJSON('/api/users?limit=10')->assertOk()->assertJsonCount(10, 'data');
    expect($response->json('meta.total'))->toBe(User::query()->count());
});

test('a user can view their profile and profile has author relationship', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('api/users/'.$user->id)->assertOk();

    expect($response->json('data'))->toHaveKeys(['id', 'first_name', 'last_name', 'email', 'author', 'locale']);
});

test('a user can update their profile', function (): void {
    $user = User::factory()->create();
    $user2 = User::factory()->create();

    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@dfo-mpo.gc.ca',
        'locale' => 'fr',
    ];

    $this->actingAs($user2)->putJson('api/users/'.$user->id, $data)->assertForbidden();
    $response = $this->actingAs($user)->putJson('api/users/'.$user->id, $data)->assertOk();
    expect($response->json('data'))->toMatchArray(collect($data)->only('first_name', 'last_name', 'locale')->all());
    expect($response->json('data.email'))->toBe($user->email);

    expect($response->json('data.author.data'))->toMatchArray(collect($data)->only('first_name', 'last_name')->all());
});

test('a user can see their authentication history', function (): void {
    $user = User::factory()->create();

    // login and log out the user, this should create an authentication record
    $this->postJson('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertNoContent();

    $this->postJson('/logout')->assertNoContent();

    expect($user->authentications->count())->toBe(1);

    $response = $this->actingAs($user)->getJson('api/user/authentications')->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

test('a user can get their authenticated user resource', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('api/user')->assertOk();

    expect($response->json('data'))->toHaveKeys(['id', 'first_name', 'last_name', 'email', 'author', 'roles', 'permissions'])->toHaveKey('id', $user->id);
});

test('isRegionalEditor returns true for all regional editor roles', function (): void {
    $regionalRoles = [
        UserRole::NFL_EDITOR,
        UserRole::MAR_EDITOR,
        UserRole::GLF_EDITOR,
        UserRole::QUE_EDITOR,
        UserRole::ONP_EDITOR,
        UserRole::ARC_EDITOR,
        UserRole::PAC_EDITOR,
        UserRole::NCR_EDITOR,
    ];

    foreach ($regionalRoles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);

        expect($user->isRegionalEditor())->toBeTrue("Failed for role: {$role->value}");
    }
});

test('isRegionalEditor returns false for non-regional roles', function (): void {
    $nonRegionalRoles = [
        UserRole::ADMIN,
        UserRole::AUTHOR,
        UserRole::DIRECTOR,
        UserRole::EDITOR,
        UserRole::CHIEF_EDITOR,
    ];

    foreach ($nonRegionalRoles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);

        expect($user->isRegionalEditor())->toBeFalse("Failed for role: {$role->value}");
    }
});

test('isRegionalEditor returns false for user with no roles', function (): void {
    $user = User::factory()->create();

    expect($user->isRegionalEditor())->toBeFalse();
});

test('isRegionalObserver returns true for all regional observer roles', function (): void {
    $regionalRoles = [
        UserRole::NFL_OBSERVER,
        UserRole::MAR_OBSERVER,
        UserRole::GLF_OBSERVER,
        UserRole::QUE_OBSERVER,
        UserRole::ONP_OBSERVER,
        UserRole::ARC_OBSERVER,
        UserRole::PAC_OBSERVER,
        UserRole::NCR_OBSERVER,
    ];

    foreach ($regionalRoles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);

        expect($user->isRegionalObserver())->toBeTrue("Failed for role: {$role->value}");
    }
});

test('isRegionalObserver returns false for non-regional-observer roles', function (): void {
    $nonRegionalObserverRoles = [
        UserRole::ADMIN,
        UserRole::AUTHOR,
        UserRole::DIRECTOR,
        UserRole::EDITOR,
        UserRole::CHIEF_EDITOR,
        UserRole::NFL_EDITOR,
        UserRole::MAR_EDITOR,
    ];

    foreach ($nonRegionalObserverRoles as $role) {
        $user = User::factory()->create();
        $user->assignRole($role);

        expect($user->isRegionalObserver())->toBeFalse("Failed for role: {$role->value}");
    }
});

test('isRegionalObserver returns false for user with no roles', function (): void {
    $user = User::factory()->create();

    expect($user->isRegionalObserver())->toBeFalse();
});

test('hasRegionalRole returns true for regional editors', function (): void {
    $user = User::factory()->create();
    $user->assignRole(UserRole::NFL_EDITOR);

    expect($user->hasRegionalRole())->toBeTrue();
});

test('hasRegionalRole returns true for regional observers', function (): void {
    $user = User::factory()->create();
    $user->assignRole(UserRole::QUE_OBSERVER);

    expect($user->hasRegionalRole())->toBeTrue();
});

test('hasRegionalRole returns false for non-regional roles', function (): void {
    $user = User::factory()->create();
    $user->assignRole(UserRole::AUTHOR);

    expect($user->hasRegionalRole())->toBeFalse();
});

test('hasRegionalRole returns false for user with no roles', function (): void {
    $user = User::factory()->create();

    expect($user->hasRegionalRole())->toBeFalse();
});
