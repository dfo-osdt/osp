<?php

namespace App\Listeners;

use App\Events\ManuscriptRecordToReviewEvent;
use App\Mail\ManuscriptRecordToReviewMail;
use Illuminate\Support\Facades\Mail;

class SendManuscriptRecordToReviewNotifications
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
     * @param  \App\Events\ManuscriptRecordToReviewEvent  $event
     * @return void
     */
    public function handle(ManuscriptRecordToReviewEvent $event)
    {
        Mail::send(new ManuscriptRecordToReviewMail($event->manuscriptRecord, $event->divisionManagerUser));
    }
}
