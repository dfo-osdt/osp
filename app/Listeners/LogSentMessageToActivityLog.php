<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class LogSentMessageToActivityLog
{
    /**
     * Record every outgoing email - recipients, subject and full body - in the
     * activity log so sent mail is fully auditable and can be correlated with
     * the mail server logs via the message id.
     */
    public function handle(MessageSent $event): void
    {
        $message = $event->message;

        if (! $message instanceof Email) {
            return;
        }

        activity('email')
            ->withProperties([
                'message_id' => $event->sent->getMessageId(),
                'subject' => $message->getSubject(),
                'from' => $this->addresses($message->getFrom()),
                'to' => $this->addresses($message->getTo()),
                'cc' => $this->addresses($message->getCc()),
                'bcc' => $this->addresses($message->getBcc()),
                'reply_to' => $this->addresses($message->getReplyTo()),
                'text' => $this->body($message->getTextBody()),
            ])
            ->log('Email sent');
    }

    /**
     * @param  array<int, Address>  $addresses
     * @return array<int, string>
     */
    private function addresses(array $addresses): array
    {
        return array_map(
            fn (Address $address): string => $address->toString(),
            $addresses,
        );
    }

    /**
     * @param  resource|string|null  $body
     */
    private function body(mixed $body): ?string
    {
        if (is_resource($body)) {
            return (string) stream_get_contents($body);
        }

        return $body;
    }
}
