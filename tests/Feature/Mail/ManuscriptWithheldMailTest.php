<?php

use App\Mail\ManuscriptWithheldMail;
use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\User;

test('a withheld email is generated', function () {
    // Create a withheld manuscript with a withheld management review associated
    $author = Author::factory()->isFromAuthorizedDomain()->create();
    $applicant = User::factory()->isFromAuthorizedDomain()->create();
    $manuscript = ManuscriptRecord::factory()->withheld()->create(['user_id' => $applicant->id]);
    $manuscript->manuscriptAuthors()->create([
        'author_id' => $author->id,
        'is_corresponding_author' => true,
        'organization_id' => 1,
    ]);
    $manuscript->manuscriptAuthors()->create([
        'author_id' => $applicant->author->id,
        'is_corresponding_author' => false,
        'organization_id' => $applicant->author->organization_id,
    ]);
    ManuscriptAuthor::factory()
        ->count(2)
        ->create(['manuscript_record_id' => $manuscript->id]);

    // Create the email
    $mailable = new ManuscriptWithheldMail($manuscript);
    $mailable->build();

    $mailable->assertTo($manuscript->user->email);
    expect($mailable->to)->toHaveCount(1);
    $mailable->assertHasCc($author->email);
    expect(collect($mailable->cc)->pluck('address')->toArray())->not->toContain($manuscript->user->email);

});
