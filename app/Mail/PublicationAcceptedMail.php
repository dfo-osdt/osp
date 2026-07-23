<?php

namespace App\Mail;

use App\Models\Publication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PublicationAcceptedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Publication $publication)
    {
        $publication->load(
            'user',
            'user.notificationGroupMembers.member',
            'journal',
            'region',
            'publicationAuthors.author.user.notificationGroupMembers.member',
            'manuscriptRecord.managementReviewSteps.user.notificationGroupMembers.member',
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->recipientEmails()->all(),
            subject: 'Publication Accepted - Publication acceptée : '.$this->publication->title,
        );
    }

    /**
     * All users in the recipient set and their notification groups, plus
     * publication author emails that are not linked to a user account.
     *
     * @return Collection<int, string>
     */
    public function recipientEmails(): Collection
    {
        $managerUsers = $this->publication->manuscriptRecord?->managementReviewSteps->pluck('user') ?? collect();

        $authorUsers = $this->publication->publicationAuthors->pluck('author.user')->filter();

        $users = collect([$this->publication->user])
            ->merge($managerUsers)
            ->merge($authorUsers)
            ->filter();

        $userEmails = $users->pluck('email')
            ->merge($users->flatMap(fn ($user): Collection => $user->getNotificationGroupEmails()));

        $nonUserAuthorEmails = $this->publication->publicationAuthors
            ->filter(fn ($publicationAuthor): bool => $publicationAuthor->author->user === null)
            ->pluck('author.email');

        return $userEmails
            ->merge($this->publication->region->getNotificationGroupEmails())
            ->merge($nonUserAuthorEmails)
            ->filter()
            ->unique()
            ->filter(fn ($email): bool => Str::of($email)->endsWith(config('osp.allowed_registration_email_domains')))
            ->values();
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.publication.publication-accepted',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
