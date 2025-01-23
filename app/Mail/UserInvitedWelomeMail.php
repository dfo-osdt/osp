<?php

namespace App\Mail;

use App\Events\Auth\Invited;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitedWelomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public User $invitedBy;

    public string $url;

    /**
     * Create a new message instance.
     */
    public function __construct(Invited $event)
    {
        $this->user = $event->invitation->user;
        $this->invitedBy = $event->invitation->invitedByUser;
        // login url
        $this->url = url('/#/auth/login');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('email.auth.invitation.title'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.auth.user-invited-welome-mail',
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
