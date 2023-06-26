<?php

namespace App\Notifications\Authentication;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    private string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public string $token)
    {
        $this->url = config('app.frontend_url')."/#/auth/password-reset?token=$token&email=$user->email";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->
            subject(__('email.auth.reset.title'))->
            markdown('authentication.reset_password', ['url' => $this->url, 'user' => $this->user]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
