<?php

use App\Actions\Accounts\RegisterUser;
use App\DTOs\RegisterUserData;
use App\Models\Author;
use App\Models\User;

test('registration updates the names of an existing author matched by email', function (): void {
    $author = Author::factory()->create([
        'first_name' => 'Old',
        'last_name' => 'Name',
        'email' => 'jane.smith@dfo-mpo.gc.ca',
        'user_id' => null,
    ]);

    $user = RegisterUser::register(new RegisterUserData(
        first_name: 'Jane',
        last_name: 'Smith',
        email: 'jane.smith@dfo-mpo.gc.ca',
        password: 'password',
    ));

    $this->assertDatabaseHas('authors', [
        'id' => $author->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'user_id' => $user->id,
    ]);
});

test('registration updates the names of an invited user author profile', function (): void {
    $user = User::factory()->invited()->create([
        'first_name' => 'Old',
        'last_name' => 'Name',
        'email' => 'jane.smith@dfo-mpo.gc.ca',
    ]);

    RegisterUser::register(new RegisterUserData(
        first_name: 'Jane',
        last_name: 'Smith',
        email: 'jane.smith@dfo-mpo.gc.ca',
        password: 'password',
    ));

    $this->assertDatabaseHas('authors', [
        'user_id' => $user->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
    ]);
});
