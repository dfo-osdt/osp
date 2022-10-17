<?php

namespace App\Listeners;

use App\Events\ManagementReviewStepCreated;
use App\Mail\ReviewStepNotificationMail;
use Mail;

class SendManagementReviewNotification
{
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
     * @param  \App\Events\ManagementReviewStepCreated  $event
     * @return void
     */
    public function handle(ManagementReviewStepCreated $event)
    {
        Mail::queue(new ReviewStepNotificationMail($event->managementReviewStep));
    }
}
