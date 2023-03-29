<?php

namespace App\Listeners;

use App\Events\ManuscriptManagementReviewComplete;
use App\Mail\ManuscriptManagementReviewComplete as MailManuscriptManagementReviewComplete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendManuscriptManagementReviewCompleteNotification implements ShouldQueue
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
     * @return void
     */
    public function handle(ManuscriptManagementReviewComplete $event)
    {
        Mail::queue(new MailManuscriptManagementReviewComplete($event->manuscriptRecord));
    }
}
