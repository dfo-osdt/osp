<?php

use App\Mail\ManuscriptRecordSubmittedToDFO;
use App\Models\Journal;
use App\Models\Publication;
use Database\Factories\AuthorFactory;

test('a manuscript submitted email is generated', function () {
    $publication = Publication::factory()
        ->recycle(Journal::factory()->dfoSeries())
        ->withManuscript()
        ->dfoSeries()
        ->create();

    // add one author to the manuscript record that is from the allowed registration email domains
    $publication->manuscriptRecord->manuscriptAuthors()->create([
        'author_id' => AuthorFactory::new()->isFromAuthorizedDomain()->create()->id,
        'organization_id' => 1,
    ]);

    $mailable = new ManuscriptRecordSubmittedToDFO($publication->manuscriptRecord);

    $mailable->assertTo(config('osp.manuscript_submission_email'));
    expect($mailable->to)->toHaveCount(1);
    expect($mailable->cc)->toHaveCount(1);

});
