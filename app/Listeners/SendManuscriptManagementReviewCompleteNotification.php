<?php

namespace App\Listeners;

use App\Events\ManuscriptManagementReviewComplete;
use App\Mail\ManuscriptManagementReviewComplete as MailManuscriptManagementReviewComplete;
use Mail;

class SendManuscriptManagementReviewCompleteNotification
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
     * @param  \App\Events\ManuscriptManagementReviewComplete  $event
     * @return void
     */
    public function handle(ManuscriptManagementReviewComplete $event)
    {
        Mail::queue(new MailManuscriptManagementReviewComplete($event->manuscriptRecord));
    }
}
