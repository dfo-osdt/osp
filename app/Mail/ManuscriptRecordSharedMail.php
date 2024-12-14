<?php

namespace App\Mail;

use App\Models\ManuscriptRecord;
use App\Models\Shareable;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ManuscriptRecordSharedMail extends Mailable
{
    use Queueable, SerializesModels;

    public ManuscriptRecord $manuscriptRecord;

    public User $user;

    public User $sharedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(public Shareable $shareable)
    {
        // ensure that this is for a manuscript record
        if (! $shareable->shareable instanceof ManuscriptRecord) {
            throw new \Exception('Shareable is not a manuscript record.');
        }

        $this->manuscriptRecord = $shareable->shareable;
        $this->user = $shareable->user;
        $this->sharedBy = $shareable->sharingUser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {

        return new Envelope(
            subject: 'Manuscript record shared with you / Un registre de manuscrit a été partagé avec vous',
            to: [$this->user->email],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.manuscriptRecord.manuscript-record-shared',
            with: [
                'manuscriptRecord' => $this->manuscriptRecord,
                'user' => $this->user,
                'sharedBy' => $this->sharedBy,
                'can_edit' => $this->shareable->can_edit,
                'expires_at' => $this->shareable->expires_at?->format('Y-m-d'),
            ],
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
