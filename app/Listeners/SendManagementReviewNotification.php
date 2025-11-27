<?php

namespace App\Listeners;

use App\Events\ManagementReviewStepCreated;
use App\Mail\ReviewStepNotificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendManagementReviewNotification implements ShouldQueue
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
     */
    public function handle(ManagementReviewStepCreated $event): void
    {
        Mail::queue(new ReviewStepNotificationMail($event->managementReviewStep));
    }
}
