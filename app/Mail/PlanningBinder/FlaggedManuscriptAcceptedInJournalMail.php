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

class FlaggedManuscriptAcceptedInJournalMail extends Mailable
{
    use Queueable, SerializesModels;

    protected Publication $publication;

    protected User $referrer;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected PlanningBinderItemState $planningBinderItemState
    ) {
        $this->publication = Publication::findOrFail($this->planningBinderItemState->publication_id)->load(['manuscriptRecord', 'journal', 'region']);
        $this->referrer = User::findOrFail($this->planningBinderItemState->referrer_user_id);
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
            cc: [$this->referrer->email],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.planning-binder.flagged-manuscript-accepted-in-journal',
            with: [
                'publication' => $this->publication,
                'state' => $this->planningBinderItemState,
                'referrer' => $this->referrer,
            ]
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
