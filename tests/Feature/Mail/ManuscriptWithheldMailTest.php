<?php

use App\Mail\ManuscriptWithheldMail;
use App\Models\Author;
use App\Models\ManuscriptRecord;

test('a withheld email is generated', function () {
    // Create a withheld manuscript with a withheld management review associated
    $author = Author::factory()->isFromDFO()->create();
    $manuscript = ManuscriptRecord::factory()->withheld()->create();
    $manuscript->manuscriptAuthors()->create([
        'author_id' => $author->id,
        'is_corresponding_author' => true,
        'organization_id' => 1,
    ]);

    // Create the email
    $mailable = new ManuscriptWithheldMail($manuscript);
    $mailable->build();

    $mailable->assertTo($manuscript->user->email);
    $mailable->assertHasCc($author->email);
    expect($mailable->to)->toHaveCount(1);
});
