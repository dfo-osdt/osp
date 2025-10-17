<?php

namespace App\Mail\PlanningBinder;

use App\Enums\ManuscriptRecordType;
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

        $cc = config('osp.manuscript_submission_email');
        if (empty($cc)) {
            throw new \Exception('The manuscript submission email address is not set.');
        }

        $title = match ($this->planningBinderItemState->manuscript_record_type) {
            ManuscriptRecordType::PRIMARY => 'Manuscript Flagged for Planning Binder Accepted In Journal - Manuscrit identifié pour le classeur de planification accepté dans un journal',
            ManuscriptRecordType::SECONDARY => 'Manuscript Flagged for Planning Binder Accepted in DFO Journal - Manuscrit identifié pour le classeur de planification accepté dans un journal du MPO',
            default => 'Manuscript Flagged for Planning Binder Accepted In Journal - Manuscrit identifié pour le classeur de planification accepté dans un journal',
        };

        return new Envelope(
            subject: $title,
            to: [$this->referrer->email],
            cc: [$cc],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        $markdownTemplate = match ($this->planningBinderItemState->manuscript_record_type) {
            ManuscriptRecordType::PRIMARY => 'mail.planning-binder.flagged-manuscript-accepted-in-journal',
            ManuscriptRecordType::SECONDARY => 'mail.planning-binder.flagged-manuscript-accepted-by-dfo',
            default => 'mail.planning-binder.flagged-manuscript-accepted-in-journal',
        };

        return new Content(
            markdown: $markdownTemplate,
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
