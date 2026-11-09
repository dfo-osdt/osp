<?php

namespace App\Mail;

use App\Models\Publication;
use App\Services\TitleMarkup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class PublicationAcceptedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Publication $publication)
    {
        $publication->load('journal');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->recipientEmails()->all(),
            subject: 'Publication Accepted - Publication acceptée : '.TitleMarkup::toPlain($this->publication->title),
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
