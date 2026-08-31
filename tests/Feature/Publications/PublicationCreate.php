<?php

use App\Actions\CreatePublicationFromManuscript;
use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\Journal;
use App\Models\ManuscriptRecord;

test('primary mrf creates not open access publication when accepted', function (): void {
    $mrf = ManuscriptRecord::factory()->create([
        'type' => ManuscriptRecordType::PRIMARY,
        'status' => ManuscriptRecordStatus::ACCEPTED,
    ]);
    $journal = Journal::factory()->create();
    $publication = CreatePublicationFromManuscript::handle($mrf, $journal);

    expect($publication->is_open_access)->toBeFalse();
});

test('secondary mrf creates open access publication when accepted', function (): void {
    $mrf = ManuscriptRecord::factory()->create([
        'type' => ManuscriptRecordType::SECONDARY,
        'status' => ManuscriptRecordStatus::ACCEPTED,
    ]);
    $journal = Journal::factory()->create();
    $publication = CreatePublicationFromManuscript::handle($mrf, $journal);

    expect($publication->is_open_access)->toBeTrue();
});
