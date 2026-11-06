<?php

namespace App\Mail;

use App\Models\ManuscriptReviewContributor;
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

/**
 * Tells a reviewer or editor that the report they contributed to is published,
 * so they can add the contribution to ORCID, or connect ORCID to get credit for it.
 */
class ReviewContributionPublishedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;

    public Publication $publication;

    /**
     * Whether the author's ORCID connection lets OSP add the contribution, decided
     * when the publication is published (not when the queued mail is sent).
     */
    public bool $canAddToOrcid;

    /**
     * Create a new message instance.
     */
    public function __construct(public ManuscriptReviewContributor $contributor)
    {
        $contributor->loadMissing('author.user', 'manuscriptRecord.publication.journal');

        $this->user = $contributor->author->user;
        $this->publication = $contributor->manuscriptRecord->publication;
        $this->canAddToOrcid = $contributor->author->hasValidOrcidCredentials();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->canAddToOrcid
            ? 'Add your review contribution to ORCID - Ajoutez votre contribution d\'évaluation à ORCID : '
            : 'Get ORCID credit for your review contribution - Obtenez la reconnaissance ORCID de votre contribution d\'évaluation : ';

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
            markdown: 'mail.publication.review-contribution-published',
            with: [
                'url' => config('app.frontend_url').'#/auth/login?'.http_build_query([
                    'email' => $this->user->email,
                    'redirect' => '/settings/author/orcid-reviews',
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
