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
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ManuscriptRecordToReviewEvent $event): void
    {
        Mail::queue(new ManuscriptRecordToReviewMail($event->manuscriptRecord, $event->divisionManagerUser));
    }
}
