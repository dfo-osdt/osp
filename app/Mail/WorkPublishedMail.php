<?php

namespace App\Mail;

use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use App\Services\TitleMarkup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Tells an author that their report is published, so they can add it to ORCID
 * as a work, or connect ORCID to add it.
 */
class WorkPublishedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;

    public Publication $publication;

    /**
     * Whether the author's ORCID connection lets OSP add the work, decided when
     * the publication is published (not when the queued mail is sent).
     */
    public bool $canAddToOrcid;

    /**
     * Create a new message instance.
     */
    public function __construct(public PublicationAuthor $publicationAuthor)
    {
        $publicationAuthor->loadMissing('author.user', 'publication.journal');

        $this->user = $publicationAuthor->author->user;
        $this->publication = $publicationAuthor->publication;
        $this->canAddToOrcid = $publicationAuthor->author->hasValidOrcidCredentials();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->canAddToOrcid
            ? 'Add your publication to ORCID - Ajoutez votre publication à ORCID : '
            : 'Connect ORCID to add your publication - Connectez ORCID pour ajouter votre publication : ';

        return new Envelope(
            to: [$this->user->email],
            subject: $subject.TitleMarkup::toPlain($this->publication->title),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.publication.work-published',
            with: [
                'url' => config('app.frontend_url').'#/auth/login?'.http_build_query([
                    'email' => $this->user->email,
                    'redirect' => '/settings/author/orcid-works',
                ], encoding_type: PHP_QUERY_RFC3986),
            ],
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
