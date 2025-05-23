<?php

namespace App\Mail\PlanningBinder;

use App\Models\Publication;
use App\Models\User;
use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlaggedManuscriptAcceptedInJournal extends Mailable
{
    use Queueable, SerializesModels;

    protected Publication $publication;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected User $referer,
        protected PlanningBinderItemState $planningBinderItemState
    ) {
        $this->publication = Publication::findOrFail($this->planningBinderItemState->publication_id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {

        $to = config('osp.manuscript_submission_email');
        if (empty($to)) {
            throw new \Exception('The manuscript submission email address is not set.');
        }

        return new Envelope(
            subject: 'Manuscript Flagged for Planning Binder Accepted In Journal - Manuscrit identifié pour le classeur de planification accepté dans un journal',
            to: [$to],
            cc: [$this->referer->email],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.planning-binder.flagged-manuscript-accepted-in-journal',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
