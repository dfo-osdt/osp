<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Email sent to the Manuscript Management Review contact when the author unsubmits a manuscript for review and returns it to draft.
 */
class ManuscriptManagementReviewUnsubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public ManuscriptRecord $manuscriptRecord, public User $reviewUser)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Manuscript Unsubmitted from Manuscript Management Review - Manuscrit retiré de l\'examen de gestion du manuscrit : '.$this->manuscriptRecord->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.manuscriptRecord.manuscript-unsubmitted-from-management-review',
            with: [
                'manuscriptRecord' => $this->manuscriptRecord,
                'user' => $this->reviewUser,
            ],
        );
    }
}
