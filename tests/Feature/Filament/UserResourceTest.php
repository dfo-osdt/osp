<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use Tests\TestCase;
use Livewire\livewire;



test('An unauthorized user cannot access the librarium pages', function ($path) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get($path);
    $response->assertForbidden();
})->with([
    '/librarium',
    '/librarium/users'
]);

test('An admin can access the librarium pages', function (string $path) {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get($path);
    $response->assertOk();
})->with([
    '/librarium',
    '/librarium/users'
]);

test('An admin cannot access user create page', function () {
    $admin = User::factory()->create();
    $admin-> assignRole('admin');

    $response = $this->actingAs($admin)->get('/librarium/users/create');
    $response->assertForbidden();
});

test('An admin cannot call create method', function () {
    $admin = User::factory()->create();
    $admin-> assignRole('admin');

    $user = User::factory()->create();

    $response = $this->actingAs($admin)->delete(route('filament.librarium.resources.users.create', $user));
    $response->assertStatus(405);
});

test('An admin can edit an existing user', function () {
    $admin = User::factory()->create();
    $admin-> assignRole('admin');

    $user = User::factory()->create();

    livewire(UserResouce\Pages\EditUser::class, [
	'user' => $user->getRouteKey(),
    ])->assertFormSet([
	'id' => $user->id,
	'first_name' => $user->first_name,
	]);
});
