<?php

namespace Tests\Feature\Librarium;

use App\Filament\Resources\Regions\Pages\EditRegion;
use App\Filament\Resources\Regions\Pages\ListRegions;
use App\Models\Region;
use App\Models\User;

describe('Access control for Region Resources', function (): void {

    beforeEach(function (): void {
        $this->record = Region::query()->first();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('Admin can render the index page', function (): void {
        $this->actingAs($this->admin)->get(ListRegions::getUrl())->assertOk();
    });

    it('Admin can render the edit page', function (): void {
        $this->actingAs($this->admin)->get(EditRegion::getUrl(['record' => $this->record->getRouteKey()]))->assertOk();
    });
});

describe('List Regions table features', function (): void {

    beforeEach(function (): void {
        $this->records = Region::all();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('Columns are displayed', function (): void {
        collect([
            'name_en',
            'name_fr',
            'slug',
            'enforce_secondary_review_deadline',
        ])->each(fn ($column) => $this->actingAs($this->admin)->livewire(ListRegions::class)
            ->assertTableColumnExists($column)
            ->assertCanRenderTableColumn($column));
    });

    it('Columns can be sorted', function (): void {
        collect([
            'name_en',
            'name_fr',
        ])->each(function (string $column): void {
            $sortedAsc = Region::query()->orderBy($column)->get();
            $sortedDesc = Region::query()->orderByDesc($column)->get();

            $this->actingAs($this->admin)->livewire(ListRegions::class)
                ->sortTable($column)
                ->assertCanSeeTableRecords($sortedAsc, inOrder: true)
                ->sortTable($column, 'desc')
                ->assertCanSeeTableRecords($sortedDesc, inOrder: true);
        });
    });

    it('Columns can be searched', function (): void {
        collect([
            'name_en',
            'name_fr',
        ])->each(function ($column): void {
            $value = $this->records->first()->{$column};

            $this->actingAs($this->admin)->livewire(ListRegions::class)
                ->searchTable($value)
                ->assertCanSeeTableRecords($this->records->where($column, $value))
                ->assertCanNotSeeTableRecords($this->records->where($column, '!=', $value));
        });
    });

    it('Delete Region button cannot be rendered', function (): void {
        $this->actingAs($this->admin)->get(ListRegions::getUrl())
            ->assertDontSee('Delete');
    });

    it('Create Region button cannot be rendered', function (): void {
        $this->actingAs($this->admin)->get(ListRegions::getUrl())
            ->assertDontSee('New region');
    });
});

describe('Edit Region page features', function (): void {

    beforeEach(function (): void {
        $this->record = Region::query()->first();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('Delete Region button cannot be rendered', function (): void {
        $this->actingAs($this->admin)->get(ListRegions::getUrl())
            ->assertDontSee('Delete');
    });

    it('Can edit enforce_secondary_review_deadline field', function (): void {
        // Ensure initial state is false
        $this->record->update(['enforce_secondary_review_deadline' => false]);

        $this->actingAs($this->admin)->livewire(EditRegion::class, ['record' => $this->record->getRouteKey()])
            ->fillForm([
                'enforce_secondary_review_deadline' => true,
            ])
            ->call('save')
            ->assertNotified();

        $this->actingAs($this->admin)->assertDatabaseHas(Region::class, [
            'id' => $this->record->id,
            'enforce_secondary_review_deadline' => true,
        ]);
    });

    it('Can toggle enforce_secondary_review_deadline from true to false', function (): void {
        // Ensure initial state is true
        $this->record->update(['enforce_secondary_review_deadline' => true]);

        $this->actingAs($this->admin)->livewire(EditRegion::class, ['record' => $this->record->getRouteKey()])
            ->fillForm([
                'enforce_secondary_review_deadline' => false,
            ])
            ->call('save')
            ->assertNotified();

        $this->actingAs($this->admin)->assertDatabaseHas(Region::class, [
            'id' => $this->record->id,
            'enforce_secondary_review_deadline' => false,
        ]);
    });

    it('Cannot edit protected fields', function (): void {
        $originalName = $this->record->name_en;
        $originalSlug = $this->record->slug;

        $this->actingAs($this->admin)->livewire(EditRegion::class, ['record' => $this->record->getRouteKey()])
            ->assertFormFieldDoesNotExist('name_en')
            ->assertFormFieldDoesNotExist('name_fr')
            ->assertFormFieldDoesNotExist('slug');

        // Ensure values haven't changed
        $this->record->refresh();
        expect($this->record->name_en)->toBe($originalName);
        expect($this->record->slug)->toBe($originalSlug);
    });
});
