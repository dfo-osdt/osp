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
     *
     * @return void
     */
    public function __construct(Invited $event)
    {
        $this->user = $event->invitation->user;
        $this->url = $event->invitation->getSignedLink();
        $this->invitedBy = $event->invitation->invitedBy;
        $this->password = $event->password;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: __('Invitation to join the Open Science Portal'),
            to: []
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'mail.user-invited-mail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
