<?php

namespace App\Mail;

use App\Enums\ManuscriptRecordType;
use App\Models\Publication;
use App\Models\User;
use App\Services\TitleMarkup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

/**
 * Tells the people involved in a publication, and its region, that someone
 * marked it as published. Secondary publications are published by Science
 * Publications, so the mail names the team rather than the person.
 */
class PublicationPublishedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Publication $publication, public User $publishedBy)
    {
        $publication->load('journal', 'manuscriptRecord');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->recipientEmails()->all(),
            subject: 'Publication Published - Publication publiée : '.TitleMarkup::toPlain($this->publication->title),
        );
    }

    /**
     * @return Collection<int, string>
     */
    public function recipientEmails(): Collection
    {
        return $this->publication->notificationRecipientEmails();
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: $this->publication->manuscriptRecord?->type === ManuscriptRecordType::SECONDARY
                ? 'mail.publication.publication-published-secondary'
                : 'mail.publication.publication-published',
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
