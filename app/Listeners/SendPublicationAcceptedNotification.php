<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PublicationAccepted;
use App\Mail\PublicationAcceptedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendPublicationAcceptedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(PublicationAccepted $event): void
    {
        $mail = new PublicationAcceptedMail($event->publication);

        // Only queue the mail if there is at least one recipient.
        if ($mail->recipientEmails()->isEmpty()) {
            return;
        }

        Mail::queue($mail);
    }
}
