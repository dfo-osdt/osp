<?php

namespace App\Mail\PlanningBinder;

use App\Models\ManuscriptRecord;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ItemFlaggedForPlanningBinder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;

    public ?ManuscriptRecord $manuscriptRecord = null;

    public ?Publication $publication = null;

    /**
     * Create a new message instance.
     */
    public $item;

    public function __construct(User $user, Model $item)
    {
        $this->user = $user;

        if ($item instanceof ManuscriptRecord) {
            $this->manuscriptRecord = $item;
        } elseif ($item instanceof Publication) {
            $this->publication = $item;
        }
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

        if ($this->manuscriptRecord) {
            $subject = 'Manuscript Record Flagged for Planning Binder - Manuscrit identifié pour le classeur de planification';
        } elseif ($this->publication) {
            $subject = 'Publication Flagged for Planning Binder - Publication identifiée pour le classeur de planification';
        } else {
            throw new \Exception('Unknown item type for Planning Binder email.');
        }

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
                'publication' => $this->publication,
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
