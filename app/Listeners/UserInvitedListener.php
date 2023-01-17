<?php

namespace App\Listeners;

use App\Events\Auth\Invited;
use App\Mail\UserInvitedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class UserInvitedListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     *
     * @return void
     */
    public function handle(Invited $event)
    {
        Mail::to($event->invitation->user)->queue(new UserInvitedMail($event));
    }
}
