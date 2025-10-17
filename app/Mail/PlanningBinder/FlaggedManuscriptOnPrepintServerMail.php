<?php

namespace App\Mail\PlanningBinder;

use App\Models\ManuscriptRecord;
use App\Models\User;
use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlaggedManuscriptOnPrepintServerMail extends Mailable
{
    use Queueable, SerializesModels;

    protected ManuscriptRecord $manuscriptRecord;

    protected User $referrer;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected PlanningBinderItemState $planningBinderItemState

    ) {
        $this->manuscriptRecord = ManuscriptRecord::where('ulid', $this->planningBinderItemState->manuscript_record_ulid)->firstOrFail()->load(['region']);
        $this->referrer = User::findOrFail($this->planningBinderItemState->referrer_user_id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {

        $cc = config('osp.manuscript_submission_email');
        if (empty($cc)) {
            throw new \Exception('The manuscript submission email address is not set.');
        }

        return new Envelope(
            subject: 'Manuscript Flagged for Planning Binder posted on Prepint Server - Manuscrit identifié pour le classeur de planification publié un serveur de prépublication',

            to: [$this->referrer->email],
            cc: [$cc],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.planning-binder.flagged-manuscript-on-prepint-server',
            with: [
                'manuscript' => $this->manuscriptRecord,
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
