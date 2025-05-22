<?php

namespace App\Mail\PlanningBinder;

use App\Models\ManuscriptRecord;
use App\Models\Publication;
use App\Models\User;
use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;

class ManuscriptFlaggedForPlanningBinder extends Mailable implements ShouldQueue
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
        $to = config('osp.manuscript_submission_email');
        if (empty($to)) {
            throw new \Exception('The manuscript submission email address is not set.');
        }

        $subject = 'Manuscript Record Flagged for Planning Binder - Manuscrit identifié pour le classeur de planification';

        return new Envelope(
            subject: $subject,
            to: [$to],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.planning-binder.item-flagged-for-planning-binder',
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
