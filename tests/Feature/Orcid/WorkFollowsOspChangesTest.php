<?php

use App\Actions\DeletePublication;
use App\Enums\ManuscriptRecordType;
use App\Enums\OrcidSyncIntent;
use App\Enums\Permissions\UserRole;
use App\Filament\Resources\Journals\Pages\EditJournal;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\Activitylog\Models\Activity;
use VincentAuger\OrcidSdk\Requests\PeerReviews\DeletePeerReview;
use VincentAuger\OrcidSdk\Requests\Works\CreateWork;
use VincentAuger\OrcidSdk\Requests\Works\DeleteWork;
use VincentAuger\OrcidSdk\Requests\Works\UpdateWork;

/**
 * A publication author whose work is on their ORCID record with put-code 4567:
 * a published report with a DOI in a flagged DFO series
 *
 * @param  array<string, mixed>  $attributes
 */
function syncedWork(array $attributes = [], ?Publication $publication = null): PublicationAuthor
{
    $publication ??= Publication::factory()->published()->create([
        'title' => 'Stock assessment of Atlantic cod',
        'doi' => '10.60825/abc-123',
        'manuscript_record_id' => ManuscriptRecord::factory()->create(['type' => ManuscriptRecordType::SECONDARY])->id,
        'journal_id' => Journal::factory()->dfoSeries()->create(['issn' => '1919-5079', 'orcid_sync_enabled' => true])->id,
    ]);

    $publicationAuthor = PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => Author::factory()->withOrcidConnection()->create()->id,
    ]);
    $publicationAuthor->forceFill([
        'orcid_sync' => OrcidSyncIntent::WANTED,
        'orcid_putcode' => 4567,
        'orcid_updated_at' => now()->addMinute(),
        ...$attributes,
    ])->save();

    return $publicationAuthor;
}

function publicationAuthorUrl(PublicationAuthor $publicationAuthor): string
{
    return "api/publications/{$publicationAuthor->publication_id}/publication-authors/{$publicationAuthor->id}";
}

test('removing an author deletes their ORCID work, then the publication author', function (): void {
    $mockClient = Saloon::fake([DeleteWork::class => MockResponse::make('', 204)]);
    $publicationAuthor = syncedWork();

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->deleteJson(publicationAuthorUrl($publicationAuthor))
        ->assertNoContent();

    $mockClient->assertSent(fn (DeleteWork $request, $response): bool => str_ends_with($response->getPendingRequest()->getUrl(), '/work/4567'));
    expect(PublicationAuthor::withTrashed()->find($publicationAuthor->id))->toBeNull();
});

test('changing a published publication\'s title, DOI, date or series updates its ORCID works', function (array $changes, Closure $sentChange): void {
    $mockClient = Saloon::fake([UpdateWork::class => MockResponse::make(['put-code' => 4567])]);
    $publicationAuthor = syncedWork();
    $publication = $publicationAuthor->publication;

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/publications/{$publication->id}", ['published_on' => $publication->published_on->toDateString(), ...$changes])
        ->assertOk();

    $mockClient->assertSent(fn (UpdateWork $request): bool => $request->body()->get('put-code') === 4567 && $sentChange($request->body()->all()));
    expect($publicationAuthor->refresh()->needsSyncWithOrcid())->toBeFalse();
})->with([
    'title' => [['title' => 'Stock assessment of Atlantic cod in 2025'], fn (array $body): bool => $body['title']['title'] === ['value' => 'Stock assessment of Atlantic cod in 2025']],
    'DOI' => [['doi' => 'https://doi.org/10.60825/def-456'], fn (array $body): bool => $body['url'] === ['value' => 'https://doi.org/10.60825/def-456']],
    'published date' => [['published_on' => '2025-06-01'], fn (array $body): bool => $body['publication-date'] === ['year' => ['value' => '2025'], 'month' => ['value' => '06'], 'day' => ['value' => '01']]],
    'series' => [fn (): array => ['journal_id' => Journal::factory()->dfoSeries()->create(['title' => 'Science Response', 'orcid_sync_enabled' => true])->id], fn (array $body): bool => $body['journal-title'] === ['value' => 'Science Response']],
]);

test('a publication change that does not affect ORCID works leaves them alone', function (): void {
    $mockClient = Saloon::fake([]);
    $publication = syncedWork()->publication;

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/publications/{$publication->id}", ['published_on' => $publication->published_on->toDateString(), 'issue_number' => '42'])
        ->assertOk();

    $mockClient->assertNothingSent();
});

test('the ORCID work is deleted when its publication moves to a series that does not count, or loses its DOI', function (Closure $changes): void {
    $mockClient = Saloon::fake([DeleteWork::class => MockResponse::make('', 204)]);
    $publicationAuthor = syncedWork();
    $publication = $publicationAuthor->publication;

    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->putJson("api/publications/{$publication->id}", ['published_on' => $publication->published_on->toDateString(), ...$changes()])
        ->assertOk();

    $mockClient->assertSent(DeleteWork::class);
    $publicationAuthor->refresh();
    expect($publicationAuthor->orcid_putcode)->toBeNull()
        ->and($publicationAuthor->orcid_sync)->toBe(OrcidSyncIntent::WANTED);
})->with([
    'journal not flagged' => [fn (): array => ['journal_id' => Journal::factory()->dfoSeries()->create(['issn' => '1919-5079'])->id]],
    'DOI removed' => [fn (): array => ['doi' => null]],
]);

test('renaming a series updates the ORCID works of its publications', function (): void {
    $mockClient = Saloon::fake([UpdateWork::class => MockResponse::make(['put-code' => 4567])]);
    $publicationAuthor = syncedWork();

    $this->actingAs(User::factory()->withRoles([UserRole::ADMIN])->create())
        ->livewire(EditJournal::class, ['record' => $publicationAuthor->publication->journal->getRouteKey()])
        ->fillForm(['title' => 'Science Response'])
        ->call('save')
        ->assertHasNoFormErrors();

    $mockClient->assertSent(fn (UpdateWork $request): bool => $request->body()->get('journal-title') === ['value' => 'Science Response']);
    expect($publicationAuthor->refresh()->needsSyncWithOrcid())->toBeFalse();
});

test('turning off ORCID sync for a series warns about its works and peer reviews on ORCID, then deletes them once confirmed', function (): void {
    $mockClient = Saloon::fake([DeleteWork::class => MockResponse::make('', 204)]);
    $publicationAuthor = syncedWork();
    $publication = $publicationAuthor->publication;
    syncedWork(publication: $publication);
    $contributor = ManuscriptReviewContributor::factory()->create(['manuscript_record_id' => $publication->manuscript_record_id]);
    $contributor->forceFill(['orcid_sync' => OrcidSyncIntent::WANTED, 'orcid_putcode' => 7890])->save();
    $mockClient->addResponse(MockResponse::make('', 204), DeletePeerReview::class);
    $admin = User::factory()->withRoles([UserRole::ADMIN])->create();

    $page = $this->actingAs($admin)
        ->livewire(EditJournal::class, ['record' => $publication->journal->getRouteKey()])
        ->fillForm(['orcid_sync_enabled' => false])
        ->call('save')
        ->assertActionMounted('turnOffOrcidSync')
        ->assertMountedActionModalSee('2 works and 1 peer review from this series are on authors\' ORCID records');

    $mockClient->assertNothingSent();

    $page->callMountedAction()->assertHasNoFormErrors();

    $mockClient->assertSentCount(2, DeleteWork::class);
    expect($publicationAuthor->refresh()->orcid_putcode)->toBeNull();
    $activity = Activity::query()->where('description', 'journal.orcid-sync-turned-off')->sole();
    expect($activity->causer->is($admin))->toBeTrue()
        ->and($activity->properties->all())->toBe(['contributions_on_orcid' => 1, 'works_on_orcid' => 2]);
});

test('turning ORCID sync back on for a series adds its wanted works to ORCID again', function (): void {
    $mockClient = Saloon::fake([CreateWork::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/work/8910'])]);
    $publicationAuthor = syncedWork(['orcid_putcode' => null, 'orcid_updated_at' => null]);
    $journal = $publicationAuthor->publication->journal;
    $journal->update(['orcid_sync_enabled' => false]);

    $this->actingAs(User::factory()->withRoles([UserRole::ADMIN])->create())
        ->livewire(EditJournal::class, ['record' => $journal->getRouteKey()])
        ->fillForm(['orcid_sync_enabled' => true])
        ->call('save')
        ->assertHasNoFormErrors();

    $mockClient->assertSentCount(1, CreateWork::class);
    expect($publicationAuthor->refresh()->orcid_putcode)->toBe(8910);
});

test('turning off ORCID sync for a series with only works on ORCID asks for confirmation', function (): void {
    $publicationAuthor = syncedWork();

    $this->actingAs(User::factory()->withRoles([UserRole::ADMIN])->create())
        ->livewire(EditJournal::class, ['record' => $publicationAuthor->publication->journal->getRouteKey()])
        ->fillForm(['orcid_sync_enabled' => false])
        ->call('save')
        ->assertActionMounted('turnOffOrcidSync')
        ->assertMountedActionModalSee('1 work from this series is on an author\'s ORCID record');
});

test('changing the author list updates the co-authors\' ORCID works', function (Closure $change): void {
    $mockClient = Saloon::fake([
        UpdateWork::class => MockResponse::make(['put-code' => 4567]),
        DeleteWork::class => MockResponse::make('', 204),
    ]);
    $publicationAuthor = syncedWork();
    $coAuthor = PublicationAuthor::factory()->create([
        'publication_id' => $publicationAuthor->publication_id,
        'author_id' => Author::factory()->create(['first_name' => 'Josiah', 'last_name' => 'Carberry'])->id,
        'organization_id' => Organization::factory()->create(['name_en' => 'Fisheries and Oceans Canada'])->id,
    ]);
    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create());

    $expectedCreditNames = $change($coAuthor);

    $mockClient->assertSent(fn (UpdateWork $request): bool => $request->body()->get('put-code') === 4567
        && array_column($request->body()->get('contributors')['contributor'], 'credit-name') === array_map(fn (string $name): array => ['value' => $name], [$publicationAuthor->author->full_name, ...$expectedCreditNames]));
    expect($publicationAuthor->refresh()->needsSyncWithOrcid())->toBeFalse();
})->with([
    'author added' => [function (PublicationAuthor $coAuthor): array {
        test()->postJson("api/publications/{$coAuthor->publication_id}/publication-authors", [
            'author_id' => Author::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith'])->id,
        ])->assertCreated();

        return ['Josiah Carberry', 'Jane Smith'];
    }],
    'author edited' => [function (PublicationAuthor $coAuthor): array {
        test()->putJson(publicationAuthorUrl($coAuthor), ['is_group_author' => true])->assertOk();

        return ['Fisheries and Oceans Canada'];
    }],
    'author removed' => [function (PublicationAuthor $coAuthor): array {
        test()->deleteJson(publicationAuthorUrl($coAuthor))->assertNoContent();

        return [];
    }],
]);

test('deleting a publication deletes its works on ORCID, keeping the authors\' intent', function (): void {
    $mockClient = Saloon::fake([DeleteWork::class => MockResponse::make('', 204)]);
    $publicationAuthor = syncedWork();

    DeletePublication::handle($publicationAuthor->publication);

    $mockClient->assertSent(DeleteWork::class);
    $publicationAuthor = PublicationAuthor::withTrashed()->findOrFail($publicationAuthor->id);
    expect($publicationAuthor->orcid_putcode)->toBeNull()
        ->and($publicationAuthor->orcid_sync)->toBe(OrcidSyncIntent::WANTED);
});

test('restoring a deleted publication adds its wanted works to ORCID again', function (): void {
    $mockClient = Saloon::fake([
        DeleteWork::class => MockResponse::make('', 204),
        CreateWork::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/work/8910']),
    ]);
    $publicationAuthor = syncedWork();
    $publication = $publicationAuthor->publication;
    DeletePublication::handle($publication);

    $this->artisan('osp:restore-soft-deleted-publication', ['id' => $publication->id])
        ->expectsConfirmation('Do you want to restore this publication?', 'yes')
        ->assertSuccessful();

    $mockClient->assertSentCount(1, CreateWork::class);
    $publicationAuthor->refresh();
    expect($publicationAuthor->trashed())->toBeFalse()
        ->and($publicationAuthor->orcid_putcode)->toBe(8910);
});

test('restoring a deleted publication does not bring back an author removed before it was deleted', function (): void {
    $this->freezeSecond();
    Saloon::fake([
        UpdateWork::class => MockResponse::make(['put-code' => 4567]),
        DeleteWork::class => MockResponse::make('', 204),
        CreateWork::class => MockResponse::make('', 201, ['Location' => 'https://api.orcid.org/v3.0/0000-0002-1825-0097/work/8910']),
    ]);
    $publicationAuthor = syncedWork();
    $publication = $publicationAuthor->publication;
    $removedAuthor = syncedWork(publication: $publication);
    $removedAuthor->author->clearOrcidToken();
    $this->actingAs(User::factory()->withRoles([UserRole::EDITOR])->create())
        ->deleteJson(publicationAuthorUrl($removedAuthor))
        ->assertNoContent();
    $this->travel(1)->minute();
    DeletePublication::handle($publication);

    $this->artisan('osp:restore-soft-deleted-publication', ['id' => $publication->id])
        ->expectsConfirmation('Do you want to restore this publication?', 'yes')
        ->assertSuccessful();

    expect($publicationAuthor->refresh()->trashed())->toBeFalse()
        ->and($removedAuthor->refresh()->trashed())->toBeTrue()
        ->and($removedAuthor->isRemovedFromPublication())->toBeTrue();
});

test('the purge command keeps removed authors whose work is still on ORCID', function (): void {
    Saloon::fake([]);
    $stillOnOrcid = syncedWork();
    $stillOnOrcid->author->clearOrcidToken();
    $neverSynced = syncedWork(['orcid_putcode' => null]);
    PublicationAuthor::query()->whereKey([$stillOnOrcid->id, $neverSynced->id])->delete();

    $this->artisan('osp:purge-soft-deleted-publication-authors', ['--force' => true])->assertSuccessful();

    expect(PublicationAuthor::withTrashed()->find($stillOnOrcid->id))->not->toBeNull()
        ->and(PublicationAuthor::withTrashed()->find($neverSynced->id))->toBeNull();
});

test('a removed author waiting for their ORCID work\'s removal can be added again', function (): void {
    $mockClient = Saloon::fake([]);
    $publicationAuthor = syncedWork();
    $publicationAuthor->author->clearOrcidToken();
    $editor = User::factory()->withRoles([UserRole::EDITOR])->create();
    $this->actingAs($editor)->deleteJson(publicationAuthorUrl($publicationAuthor))->assertNoContent();

    $this->actingAs($editor)->getJson("api/publications/{$publicationAuthor->publication_id}/publication-authors")
        ->assertOk()
        ->assertJsonCount(0, 'data');
    $this->actingAs($editor)->postJson("api/publications/{$publicationAuthor->publication_id}/publication-authors", [
        'author_id' => $publicationAuthor->author_id,
    ])->assertCreated();

    $mockClient->assertNothingSent();
    expect($publicationAuthor->refresh()->trashed())->toBeTrue()
        ->and($publicationAuthor->orcid_putcode)->toBe(4567);
});
