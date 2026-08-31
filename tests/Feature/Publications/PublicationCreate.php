<?php

use App\Actions\CreatePublicationFromManuscript;
use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\Journal;
use App\Models\ManuscriptRecord;

test('secondary mrf creates open access publication when accepted', function (): void {
    $mrf = ManuscriptRecord::factory()->create([
        'type' => ManuscriptRecordType::SECONDARY,
        'status' => ManuscriptRecordStatus::ACCEPTED,
    ]);
    $journal = Journal::factory()->create();
    $publication = CreatePublicationFromManuscript::handle($mrf, $journal);

    expect($publication->is_open_access)->toBeTrue();
});
