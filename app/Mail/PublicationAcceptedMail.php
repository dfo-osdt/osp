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
            'journal',
            'region',
            'publicationAuthors.author',
            'manuscriptRecord.managementReviewSteps.user',
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
     * The regional notification group, the manuscript's managers (management review
     * step reviewers), and the publication's authors.
     *
     * @return Collection<int, string>
     */
    public function recipientEmails(): Collection
    {
        $regionalNotificationGroupEmails = $this->publication->region->getNotificationGroupEmails();

        $managerEmails = $this->publication->manuscriptRecord?->managementReviewSteps->pluck('user.email') ?? collect();

        $authorEmails = $this->publication->publicationAuthors->pluck('author.email');

        return $regionalNotificationGroupEmails->merge($managerEmails)->merge($authorEmails)
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
