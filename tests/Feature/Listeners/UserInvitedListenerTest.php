<?php

use App\Events\Auth\Invited;
use App\Mail\UserInvitedMail;
use App\Models\Invitation;

test('check that an invited event triggers listener and sends mail', function () {
    Mail::fake();

    $invitation = Invitation::factory()->create();

    Invited::dispatch($invitation, 'password');

    Mail::assertQueued(UserInvitedMail::class);
});
