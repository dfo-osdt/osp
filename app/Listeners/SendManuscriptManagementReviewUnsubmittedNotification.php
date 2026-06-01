<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ManuscriptManagementReviewUnsubmittedEvent;
use App\Mail\ManuscriptManagementReviewUnsubmittedMail;
use Illuminate\Support\Facades\Mail;

class SendManuscriptManagementReviewUnsubmittedNotification
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
    public function handle(ManuscriptManagementReviewUnsubmittedEvent $event): void
    {
        Mail::queue(new ManuscriptManagementReviewUnsubmittedMail($event->manuscriptRecord, $event->reviewUser));
    }
}
