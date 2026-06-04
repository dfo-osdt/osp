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
        $to = $event->reviewUsers->last();

        if (! $to) {
            return;
        }

        $event->manuscriptRecord->load('user', 'manuscriptAuthors.author');
        $event->reviewUsers->each(fn ($reviewUser) => $reviewUser->load('user'));

        $reviewEmails = $event->reviewUsers
            ->pluck('user.email')
            ->filter()
            ->values();

        $authorEmails = $event->manuscriptRecord->manuscriptAuthors
            ->pluck('author.email')
            ->filter(fn ($email): bool => $email !== null)
            ->values();

        $ccEmails = $authorEmails
            ->merge($reviewEmails);

        $ccEmails = $ccEmails
            ->unique()
            ->diff([$to->user->email])
            ->values()
            ->all();

        Mail::to($to->user->email, $to->user->fullName)
            ->cc($ccEmails)
            ->queue(new ManuscriptManagementReviewUnsubmittedMail($event->manuscriptRecord, $to->user, $event->unsubmittedBy));
    }
}
