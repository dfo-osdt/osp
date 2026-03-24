<?php

namespace App\Mail\PlanningBinder;

use App\Enums\ManagementReviewStepStatus;
use App\Models\ManuscriptRecord;
use App\Models\User;
use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ManuscriptFlaggedForPlanningBinderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ?ManuscriptRecord $manuscriptRecord = null;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public PlanningBinderItemState $planningBinderItemState)
    {
        $this->manuscriptRecord = ManuscriptRecord::query()->where('ulid', $this->planningBinderItemState->manuscript_record_ulid)->firstOrFail();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $ospEmail = config('osp.manuscript_submission_email');
        if (empty($ospEmail)) {
            throw new \Exception('The manuscript submission email address is not set.');
        }

        $ccEmails = collect([$ospEmail]);

        $completedReviewers = $this->manuscriptRecord
            ->managementReviewSteps()
            ->where('status', ManagementReviewStepStatus::COMPLETED)
            ->with('user')
            ->get()
            ->pluck('user');

        $ccEmails = $ccEmails->merge($completedReviewers->pluck('email'));

        foreach ($completedReviewers as $reviewer) {
            $ccEmails = $ccEmails->merge($reviewer->getNotificationGroupEmails());
        }

        $ccEmails = $ccEmails->unique()->diff([$this->user->email])->values();

        $subject = '[No Action Required] Manuscript Record Flagged for Planning Binder - [Aucune Action Requise] Manuscrit identifié pour le classeur de planification';

        return new Envelope(
            to: [$this->user->email],
            cc: $ccEmails->all(),
            subject: $subject
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
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
