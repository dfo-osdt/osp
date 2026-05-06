<?php

use App\Models\FunctionalArea;
use App\Models\Publication;
use App\Models\User;

test('a user can export publications to excel', function (): void {
    $user = User::factory()->create();

    $functionalArea = FunctionalArea::factory()->create();

    Publication::factory()
        ->published()
        ->withManuscript(['functional_area_id' => $functionalArea->id])
        ->withAuthors()
        ->create();

    Publication::factory()->create();

    $response = $this->actingAs($user)->get('/api/publications/export?format=excel');

    $response->assertOk();
    $response->assertDownload('publications.xlsx');
});
