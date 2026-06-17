<?php

namespace Tests\Feature\Librarium;

use App\Filament\Resources\Journals\Pages\CreateJournal;
use App\Filament\Resources\Journals\Pages\EditJournal;
use App\Filament\Resources\Journals\Pages\ListJournals;
use App\Models\Journal;
use App\Models\User;

describe('Access control for Journal Resources', function (): void {

    beforeEach(function (): void {
        $this->record = Journal::factory()->create();
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('an unauthorized user cannot access the list page', function (): void {
        $this->actingAs($this->user)->get(ListJournals::getUrl())->assertForbidden();
    });

    it('an admin can access the list page', function (): void {
        $this->actingAs($this->admin)->get(ListJournals::getUrl())->assertOk();
    });

    it('an admin can access the create page', function (): void {
        $this->actingAs($this->admin)->livewire(CreateJournal::class)->assertOk();
    });

    it('an admin can access the edit page', function (): void {
        $this->actingAs($this->admin)->livewire(EditJournal::class, ['record' => $this->record->getRouteKey()])->assertOk();
    });
});

describe('List Journals table features', function (): void {

    beforeEach(function (): void {
        $this->records = Journal::factory(5)->create();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('columns are displayed', function (): void {
        collect([
            'title',
            'scopus_source_record_id',
            'publisher',
            'issn',
        ])->each(fn ($column) => $this->actingAs($this->admin)->livewire(ListJournals::class)
            ->assertTableColumnExists($column)
            ->assertCanRenderTableColumn($column));
    });

    it('records are listed', function (): void {
        $this->actingAs($this->admin)->livewire(ListJournals::class)
            ->assertCanSeeTableRecords($this->records);
    });

    it('columns can be searched', function (): void {
        collect(['title', 'publisher'])->each(function (string $column): void {
            $value = $this->records->first()->{$column};

            $this->actingAs($this->admin)->livewire(ListJournals::class)
                ->searchTable($value)
                ->assertCanSeeTableRecords($this->records->where($column, $value))
                ->assertCanNotSeeTableRecords($this->records->where($column, '!=', $value));
        });
    });
});

describe('Create Journal', function (): void {

    beforeEach(function (): void {
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('an admin can create a journal with required fields', function (): void {
        $this->actingAs($this->admin)->livewire(CreateJournal::class)
            ->fillForm([
                'title' => 'Canadian Journal of Fisheries',
                'publisher' => 'NRC Press',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Journal::class, [
            'title' => 'Canadian Journal of Fisheries',
            'publisher' => 'NRC Press',
        ]);
    });

    it('an admin can create a journal with all fields', function (): void {
        $this->actingAs($this->admin)->livewire(CreateJournal::class)
            ->fillForm([
                'title' => 'Aquatic Sciences',
                'publisher' => 'Springer',
                'scopus_source_record_id' => '12345',
                'issn' => '1234-5678',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Journal::class, [
            'title' => 'Aquatic Sciences',
            'publisher' => 'Springer',
            'scopus_source_record_id' => '12345',
            'issn' => '1234-5678',
        ]);
    });

    it('title is required', function (): void {
        $this->actingAs($this->admin)->livewire(CreateJournal::class)
            ->fillForm([
                'title' => '',
                'publisher' => 'NRC Press',
            ])
            ->call('create')
            ->assertHasFormErrors(['title' => 'required']);
    });

    it('publisher is required', function (): void {
        $this->actingAs($this->admin)->livewire(CreateJournal::class)
            ->fillForm([
                'title' => 'Canadian Journal of Fisheries',
                'publisher' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['publisher' => 'required']);
    });
});

describe('Edit Journal', function (): void {

    beforeEach(function (): void {
        $this->record = Journal::factory()->create();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('form is pre-populated with existing data', function (): void {
        $this->actingAs($this->admin)->livewire(EditJournal::class, ['record' => $this->record->getRouteKey()])
            ->assertFormSet([
                'title' => $this->record->title,
                'publisher' => $this->record->publisher,
                'scopus_source_record_id' => $this->record->scopus_source_record_id,
                'issn' => $this->record->issn,
            ]);
    });

    it('an admin can update a journal', function (): void {
        $this->actingAs($this->admin)->livewire(EditJournal::class, ['record' => $this->record->getRouteKey()])
            ->fillForm([
                'title' => 'Updated Journal Title',
                'publisher' => 'Updated Publisher',
                'issn' => '9999-0000',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Journal::class, [
            'id' => $this->record->id,
            'title' => 'Updated Journal Title',
            'publisher' => 'Updated Publisher',
            'issn' => '9999-0000',
        ]);
    });

    it('title is required on edit', function (): void {
        $this->actingAs($this->admin)->livewire(EditJournal::class, ['record' => $this->record->getRouteKey()])
            ->fillForm(['title' => ''])
            ->call('save')
            ->assertHasFormErrors(['title' => 'required']);
    });

    it('publisher is required on edit', function (): void {
        $this->actingAs($this->admin)->livewire(EditJournal::class, ['record' => $this->record->getRouteKey()])
            ->fillForm(['publisher' => ''])
            ->call('save')
            ->assertHasFormErrors(['publisher' => 'required']);
    });

    it('an admin can delete a journal from the edit page', function (): void {
        $this->actingAs($this->admin)->livewire(EditJournal::class, ['record' => $this->record->getRouteKey()])
            ->callAction('delete')
            ->assertHasNoErrors();

        $this->assertModelMissing($this->record);
    });
});
