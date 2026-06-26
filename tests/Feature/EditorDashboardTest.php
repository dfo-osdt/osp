<?php

declare(strict_types=1);

use App\Enums\ManuscriptRecordType;
use App\Enums\Permissions\UserRole;
use App\Enums\PlanningBinder\PlanningBinderItemStatus;
use App\Models\ManuscriptRecord;
use App\Models\PlanningBinderItem;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;

function editor(): User
{
    $user = User::factory()->create();
    $user->assignRole(UserRole::EDITOR->value);

    return $user;
}

it('allows editors and chief editors to view the dashboard', function (UserRole $role): void {
    $user = User::factory()->create();
    $user->assignRole($role->value);

    $this->actingAs($user)
        ->getJson('/api/editor-dashboard')
        ->assertOk();
})->with([
    'editor' => UserRole::EDITOR,
    'chief editor' => UserRole::CHIEF_EDITOR,
]);

it('forbids regional editors and plain authors', function (UserRole $role): void {
    $user = User::factory()->create();
    $user->assignRole($role->value);

    $this->actingAs($user)
        ->getJson('/api/editor-dashboard')
        ->assertForbidden();
})->with([
    'regional editor' => UserRole::NFL_EDITOR,
    'author' => UserRole::AUTHOR,
]);

it('counts secondary MRFs awaiting submission to science publications', function (): void {
    // reviewed secondary MRFs => awaiting science publication
    ManuscriptRecord::factory()->secondary()->reviewed()->count(2)->create();
    // a primary reviewed MRF must be ignored
    ManuscriptRecord::factory()->reviewed()->create();
    // an accepted secondary MRF is past this stage and must be ignored
    ManuscriptRecord::factory()->secondary()->accepted()->create();

    $this->actingAs(editor())
        ->getJson('/api/editor-dashboard')
        ->assertOk()
        ->assertJsonPath('counts.awaiting_scientific_publication', 2);
});

it('counts and lists only secondary accepted publications in the due queue', function (): void {
    // in queue: secondary, accepted
    Publication::factory()->dfoSeries()->count(3)->create();
    // excluded: secondary but already published
    Publication::factory()->dfoSeries()->published()->create();
    // excluded: primary accepted publication
    Publication::factory()->create();

    $response = $this->actingAs(editor())
        ->getJson('/api/editor-dashboard')
        ->assertOk()
        ->assertJsonPath('counts.in_scientific_publication', 3);

    expect($response->json('data'))->toHaveCount(3);
});

it('orders the due queue oldest accepted first', function (): void {
    $newest = Publication::factory()->dfoSeries()->create(['accepted_on' => now()->subDays(2)]);
    $oldest = Publication::factory()->dfoSeries()->create(['accepted_on' => now()->subDays(40)]);
    $middle = Publication::factory()->dfoSeries()->create(['accepted_on' => now()->subDays(20)]);

    $ids = $this->actingAs(editor())
        ->getJson('/api/editor-dashboard')
        ->assertOk()
        ->json('data.*.data.id');

    expect($ids)->toBe([$oldest->id, $middle->id, $newest->id]);
});

it('flags queue items that are tracked in the planning binder', function (): void {
    $flagged = Publication::factory()->dfoSeries()->create();
    PlanningBinderItem::factory()->create([
        'plannable_type' => Publication::class,
        'plannable_id' => $flagged->id,
        'type' => ManuscriptRecordType::SECONDARY,
        'status' => PlanningBinderItemStatus::READY,
    ]);

    $unflagged = Publication::factory()->dfoSeries()->create();

    $response = $this->actingAs(editor())
        ->getJson('/api/editor-dashboard')
        ->assertOk()
        ->assertJsonPath('counts.in_planning_binder', 1);

    $byId = collect($response->json('data'))->keyBy('data.id');

    expect($byId[$flagged->id]['data']['in_planning_binder'])->toBeTrue()
        ->and($byId[$unflagged->id]['data']['in_planning_binder'])->toBeFalse();
});

it('includes catalogue number in queue rows', function (): void {
    $publication = Publication::factory()->dfoSeries()->create([
        'catalogue_number' => 'Fs97-18/409E-PDF',
    ]);

    $this->actingAs(editor())
        ->getJson('/api/editor-dashboard')
        ->assertSuccessful()
        ->assertJsonPath('data.0.data.id', $publication->id)
        ->assertJsonPath('data.0.data.catalogue_number', 'Fs97-18/409E-PDF');
});

it('includes corresponding contact name and email for queue rows', function (): void {
    $publication = Publication::factory()->dfoSeries()->create();

    PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'is_corresponding_author' => false,
    ]);

    $corresponding = PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'is_corresponding_author' => true,
    ]);

    $this->actingAs(editor())
        ->getJson('/api/editor-dashboard')
        ->assertSuccessful()
        ->assertJsonPath(
            'data.0.data.contact_name',
            trim("{$corresponding->author->first_name} {$corresponding->author->last_name}")
        )
        ->assertJsonPath('data.0.data.contact_email', $corresponding->author->email);
});
