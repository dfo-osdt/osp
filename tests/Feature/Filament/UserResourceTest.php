<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;

describe('page authorization', function () {
    it('An unauthorized user cannot render the index page', function () {
        $user = User::factory()->create();

        $this->actingAs($user)->get(ListUsers::getUrl())->assertForbidden();
    });

    it('An unauthorized user cannot render the create page', function () {
        $user = User::factory()->create();

        $this->actingAs($user)->get(CreateUser::getUrl())->assertForbidden();
    });

    it('An unauthorized user cannot render the edit page', function () {
        $user = User::factory()->create();
        $record = User::factory()->create();

        $this->actingAs($user)->livewire(EditUser::class, ['record' => $record->getRouteKey()])->assertForbidden();
    });

    it('An admin can render the index page', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get(ListUsers::getUrl())->assertOk();
    });

    it('An admin cannot render the user create page', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get(CreateUser::getUrl())->assertForbidden();
    });
})->todo(note: <<<'NOTE'
    render logout button
    logout path successful
    NOTE);

describe('list users table', function () {
    it('User Table has required columns', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        collect([
            'first_name',
            'last_name',
            'email',
            'email_verified_at',
            'active',
            'roles.name',
        ])->each(fn ($column) => $this->actingAs($admin)->livewire(ListUsers::class)->assertTableColumnExists($column));
    });

    it('Admin can render the User Table column', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        collect([
            'first_name',
            'last_name',
            'email',
            'email_verified_at',
            'active',
            'roles.name',
        ])->each(fn ($column) => $this->actingAs($admin)->livewire(ListUsers::class)->assertCanRenderTableColumn($column));
    });

    it('Admin can sort the User Table column', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $records = User::factory(5)->create();

        collect([
            'first_name',
            'last_name',
            'email_verified_at',
            'active',
        ])->each(function ($column) use ($admin, $records) {
            $this->actingAs($admin)->livewire(ListUsers::class)
                ->sortTable($column)
                ->assertCanSeeTableRecords($records->sortBy($column), inOrder: true)
                ->sortTable($column, 'desc')
                ->assertCanSeeTableRecords($records->sortByDesc($column), inOrder: true);
        });
    });

    it('Admin can search the User Table column', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $records = User::factory(5)->create();

        collect([
            'first_name',
            'last_name',
            'email',
            'roles.name',
        ])->each(function ($column) use ($admin, $records) {
            $value = $records->first()->{$column};

            $this->actingAs($admin)->livewire(ListUsers::class)
                ->searchTable($value)
                ->assertCanSeeTableRecords($records->where($column, $value))
                ->assertCanNotSeeTableRecords($records->where($column, '!=', $value));
        });
    });

    it('An admin cannot render a delete user button', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get(ListUsers::getUrl())
            ->assertDontSee('Delete');
    });
});

describe('edit user', function () {

    it('An admin can edit an existing user', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

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
    })->todo(note: <<<'NOTE'
    Add attributes: roles
NOTE);
})->todo(issue: 857,
    note: <<<'NOTE'
    validate inputs
    NOTE);
