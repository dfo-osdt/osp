<?php

namespace App\Mail;

use App\Models\ManuscriptRecord;
use App\Models\Publication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ManuscriptRecordSubmittedToDFO extends Mailable
{
    use Queueable, SerializesModels;

    public Publication $publication;

    /**
     * Create a new message instance.
     */
    public function __construct(public ManuscriptRecord $manuscriptRecord)
    {
        $manuscriptRecord->load('user', 'manuscriptAuthors.author', 'publication');
        $this->publication = $manuscriptRecord->publication;

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
        $cc = collect($this->manuscriptRecord->manuscriptAuthors)
            ->pluck('author.email')
            ->add($this->manuscriptRecord->user->email)
            ->filter(fn ($email) => Str::of($email)->endsWith(config('osp.allowed_registration_email_domains')))
            ->unique()->toArray();

        return new Envelope(
            subject: 'Manuscript Submitted - Manuscrit soumis: '.$this->manuscriptRecord->title,
            to: [$to],
            cc: $cc,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.manuscriptRecord.manuscript-submitted-to-dfo',
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
