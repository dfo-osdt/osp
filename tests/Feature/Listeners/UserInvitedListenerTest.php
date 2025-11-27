<?php

use App\Mail\UserInvitedMail;
use App\Models\Invitation;

test('check that an invited event triggers listener and sends mail', function (): void {
    Mail::fake();

    $invitation = Invitation::factory()->create();

    event(new \App\Events\Auth\Invited($invitation, 'password'));

    Mail::assertQueued(UserInvitedMail::class);
});
