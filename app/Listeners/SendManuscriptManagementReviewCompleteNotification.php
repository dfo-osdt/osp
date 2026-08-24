<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ManuscriptManagementReviewComplete;
use App\Mail\ManuscriptManagementReviewComplete as MailManuscriptManagementReviewComplete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendManuscriptManagementReviewCompleteNotification implements ShouldQueue
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
    public function handle(ManuscriptManagementReviewComplete $event): void
    {
        Mail::queue(new MailManuscriptManagementReviewComplete($event->manuscriptRecord));
    }
}
