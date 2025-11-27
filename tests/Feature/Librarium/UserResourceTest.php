<?php

namespace Tests\Feature\Librarium;

use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;

describe('Access control for User Resources', function (): void {

    beforeEach(function (): void {
        $this->record = User::factory()->create();
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('An unauthorized user cannot render the page', function ($url): void {
        $this->actingAs($this->user)->get($url)->assertForbidden();
    })->with([
        'ListUsers' => [fn (): string => ListUsers::getUrl()],
    ]);

    it('An unauthorized user cannot render the edit page', function (): void {
        $this->actingAs($this->user)->livewire(EditUser::class, ['record' => $this->record->getRouteKey()])->assertForbidden();
    });

    it('Can render the index page', function (): void {
        $this->actingAs($this->admin)->get(ListUsers::getUrl())->assertOk();
    });

    it('Can render logout button', function (): void {})->todo();
    it('Can logout path successful', function (): void {})->todo();
});

describe('List Users table features', function (): void {

    beforeEach(function (): void {
        $this->records = User::factory(5)->create();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('Columns are displayed', function (): void {
        collect([
            'first_name',
            'last_name',
            'email',
            'email_verified_at',
            'active',
            'roles.name',
        ])->each(fn ($column) => $this->actingAs($this->admin)->livewire(ListUsers::class)
            ->assertTableColumnExists($column)
            ->assertCanRenderTableColumn($column));
    });

    it('Columns can be sorted', function (): void {
        collect([
            'first_name',
            'last_name',
        ])->each(fn ($column) => $this->actingAs($this->admin)->livewire(ListUsers::class)
            ->sortTable($column)
            ->assertCanSeeTableRecords($this->records->sortBy($column), inOrder: true)
            ->sortTable($column, 'desc')
            ->assertCanSeeTableRecords($this->records->sortByDesc($column), inOrder: true));
    });

    it('Columns can be searched', function (): void {
        collect([
            'first_name',
            'last_name',
            'email',
            'roles.name',
        ])->each(function ($column): void {
            $value = $this->records->first()->{$column};

            $this->actingAs($this->admin)->livewire(ListUsers::class)
                ->searchTable($value)
                ->assertCanSeeTableRecords($this->records->where($column, $value))
                ->assertCanNotSeeTableRecords($this->records->where($column, '!=', $value));
        });
    });

    it('Delete User button cannot be rendered', function (): void {
        $this->actingAs($this->admin)->get(ListUsers::getUrl())
            ->assertDontSee('Delete');
    });
});

describe('Edit User page features', function (): void {

    beforeEach(function (): void {
        $this->record = User::factory()->create();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('Delete User button cannot be rendered', function (): void {
        $this->actingAs($this->admin)->get(ListUsers::getUrl())
            ->assertDontSee('Delete');
    });

    it('Can edit an existing user', function (): void {
        $this->actingAs($this->admin)->livewire(Edituser::class, ['record' => $this->record->getRouteKey()])
            ->fillForm([
                'email' => "{$this->record->first_name}.{$this->record->last_name}@dfo-mpo.gc.ca",
            ])
            ->assertActionExists('save')
            ->call('save')
            ->assertHasNoFormErrors();

        $this->actingAs($this->admin)->assertDatabaseHas(User::class, [
            'email' => "{$this->record->first_name}.{$this->record->last_name}@dfo-mpo.gc.ca",
        ]);
    })->todo(note: <<<'NOTE'
    Add attributes: roles
NOTE);

    it('Cannot save an invalid field', function (): void {
        //
    })->todo(
        note: <<<'NOTE'
    validate inputs
    NOTE,
        issue: 857
    );
});
