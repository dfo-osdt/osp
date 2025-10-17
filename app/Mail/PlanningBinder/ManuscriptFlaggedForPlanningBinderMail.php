<?php

namespace App\Mail\PlanningBinder;

use App\Models\ManuscriptRecord;
use App\Models\User;
use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ManuscriptFlaggedForPlanningBinderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;

    public ?ManuscriptRecord $manuscriptRecord = null;

    public PlanningBinderItemState $planningBinderItemState;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, PlanningBinderItemState $planningBinderItemState)
    {
        $this->planningBinderItemState = $planningBinderItemState;
        $this->user = $user;
        $this->manuscriptRecord = ManuscriptRecord::where('ulid', $this->planningBinderItemState->manuscript_record_ulid)->firstOrFail();
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

        $subject = '[No Action Required] Manuscript Record Flagged for Planning Binder - [Aucune Action Requise] Manuscrit identifié pour le classeur de planification';

        return new Envelope(
            subject: $subject,
            to: [$this->user->email],
            cc: [$cc]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.planning-binder.manuscript-flagged-for-planning-binder',
            with: [
                'user' => $this->user,
                'manuscript' => $this->manuscriptRecord,
                'state' => $this->planningBinderItemState,
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
