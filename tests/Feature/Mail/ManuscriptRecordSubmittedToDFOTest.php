<?php

use App\Enums\Permissions\UserRole;
use App\Mail\ManuscriptRecordSubmittedToDFO;
use App\Models\Journal;
use App\Models\Publication;
use App\Models\User;
use Database\Factories\AuthorFactory;

test('a manuscript submitted email is generated', function (): void {
    $publication = Publication::factory()
        ->recycle(Journal::factory()->dfoSeries())
        ->withManuscript([
            'region_id' => 1,
        ])
        ->dfoSeries()
        ->create();

    expect($publication->manuscriptRecord->region_id)->toBe(1);

    // add one author to the manuscript record that is from the allowed registration email domains
    $publication->manuscriptRecord->manuscriptAuthors()->create([
        'author_id' => AuthorFactory::new()->isFromAuthorizedDomain()->create()->id,
        'organization_id' => 1,
    ]);

    // add a regional editor to the manuscript record region that is from the allowed registration email domains
    $regional_editor = User::factory()->isFromAuthorizedDomain()->create();
    $regional_editor->assignRole(UserRole::NFL_EDITOR);

    $mailable = new ManuscriptRecordSubmittedToDFO($publication->manuscriptRecord);

    $mailable->assertTo(config('osp.manuscript_submission_email'));
    expect($mailable->to)->toHaveCount(1);
    // the author and application user, as well as regional editor should be cc'd in this test
    expect($mailable->cc)->toHaveCount(3);

});
