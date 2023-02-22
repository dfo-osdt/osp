<?php

namespace App\Listeners;

use App\Events\ManuscriptRecordToReviewEvent;
use App\Mail\ManuscriptRecordToReviewMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendManuscriptRecordToReviewNotifications implements ShouldQueue
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
    public function handle(ManuscriptRecordToReviewEvent $event)
    {
        Mail::queue(new ManuscriptRecordToReviewMail($event->manuscriptRecord, $event->divisionManagerUser));
    }
}
