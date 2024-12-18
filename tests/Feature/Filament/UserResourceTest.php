<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Tests\TestCase;

use function Pest\Livewire\livewire;



test('An unauthorized user cannot render the index page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(ListUsers::getUrl())->assertForbidden();
});

test('An unauthorized user cannot render the create page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(CreateUser::getUrl())->assertForbidden();
});

test('An unauthorized user cannot render the edit page', function () {
    $user = User::factory()->create();
    $record = User::factory()->create();

    $this->actingAs($user)->livewire(EditUser::class, ['record' => $record->getRouteKey()])->assertForbidden();
});

test('An admin can render the index page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->get(ListUsers::getUrl())->assertOk();
});

test('An admin cannot render the user create page', function () {
    $admin = User::factory()->create();
    $admin-> assignRole('admin');

    $this->actingAs($admin)->get(CreateUser::getUrl())->assertForbidden();
});

test('User Table has column', function (string $column) {
    $admin = User::factory()->create();
    $admin-> assignRole('admin');
    
    $this->actingAs($admin)->livewire(ListUsers::class)->assertTableColumnExists($column);
})->with([
    'first_name',
    'last_name',
    'email',
    'email_verified_at',
    'active',
    'roles.name'
]);

test('Admin can render the User Table column', function ($column) {
    $admin = User::factory()->create();
    $admin-> assignRole('admin');
    
    $this->actingAs($admin)->livewire(ListUsers::class)->assertCanRenderTableColumn($column);
})->with([
    'first_name',
    'last_name',
    'email',
    'email_verified_at',
    'active',
    'roles.name'
]);

test('Admin can sort the User Table column', function ($column) {
    $admin = User::factory()->create();
    $admin-> assignRole('admin');
    $records = User::factory(5)->create();
    
    $this->actingAs($admin)->livewire(ListUsers::class)
	 ->sortTable($column)
	 ->assertCanSeeTableRecords($records->sortBy($column), inOrder: true)
	 ->sortTable($column, 'desc')
	 ->assertCanSeeTableRecords($records->sortByDesc($column), inOrder: true);
})->with([
    'first_name',
    'last_name',
    'email_verified_at',
    'active',
]);

test('Admin can search the User Table column', function (string $column) {
    $admin = User::factory()->create();
    $admin-> assignRole('admin');
    $records = User::factory(5)->create();

    $value = $records->first()->{$column};
    
    $this->actingAs($admin)->livewire(ListUsers::class)
	 ->searchTable($value)
	 ->assertCanSeeTableRecords($records->where($column, $value))
	 ->assertCanNotSeeTableRecords($records->where($column, '!=', $value));
})->with([
    'first_name',
    'last_name',
    'email',
    'roles.name',
]);

test('An admin can edit an existing user', function () {
    $admin = User::factory()->create();
    $admin-> assignRole('admin');

    $record = User::factory()->create();
    $newRecord = User::factory()->make();

    $this->actingAs($admin)->livewire(Edituser::class, ['record' => $record->getRouteKey()])
	 ->fillForm([
	     'email' => $newRecord->email,
	 ])
	 ->assertActionExists('save')
	 ->call('save')
   	 ->assertHasNoFormErrors();

    $this->actingAs($admin)->assertDatabaseHas(User::class, [
	'email' => $newRecord->email,
    ]);
});

test('An admin cannot render a delete button', function () {
    $admin = User::factory()->create();
    $admin-> assignRole('admin');

    $this->actingAs($admin)->get(ListUsers::getUrl())
    ->assertDontSee('Delete');
});
