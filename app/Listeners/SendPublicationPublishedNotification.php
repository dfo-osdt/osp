<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PublicationPublished;
use App\Mail\PublicationPublishedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendPublicationPublishedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(PublicationPublished $event): void
    {
        $mail = new PublicationPublishedMail($event->publication, $event->publishedBy);

        // Only queue the mail if there is at least one recipient.
        if ($mail->recipientEmails()->isEmpty()) {
            return;
        }

        Mail::queue($mail);
    }
}
