<?php

namespace App\Mail;

use App\Events\Auth\Invited;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public string $url;

    public string $password;

    public User $invitedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(Invited $event)
    {
        $this->user = $event->invitation->user;
        $this->url = $event->invitation->getSignedLink();
        $this->invitedBy = $event->invitation->invitedByUser;
        $this->password = $event->password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): \Illuminate\Mail\Mailables\Envelope
    {
        return new Envelope(
            to: [],
            subject: __('email.auth.invitation.title')
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): \Illuminate\Mail\Mailables\Content
    {
        return new Content(
            markdown: 'mail.auth.user-invited-mail',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
