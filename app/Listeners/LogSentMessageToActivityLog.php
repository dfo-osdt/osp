<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Symfony\Component\Mime\Address;

class LogSentMessageToActivityLog
{
    /**
     * Record every outgoing email - recipients and subject - in the activity
     * log so sent mail is auditable and can be correlated with the mail server
     * logs via the message id.
     *
     * The message body is intentionally not stored: some emails (password
     * resets, signed verification links) embed secrets in the body that must
     * not be persisted to a readable audit table.
     */
    public function handle(MessageSent $event): void
    {
        $message = $event->message;

        activity('email')
            ->event('sent')
            ->withProperties([
                'message_id' => $event->sent->getMessageId(),
                'subject' => $message->getSubject(),
                'from' => $this->addresses($message->getFrom()),
                'to' => $this->addresses($message->getTo()),
                'cc' => $this->addresses($message->getCc()),
                'bcc' => $this->addresses($message->getBcc()),
                'reply_to' => $this->addresses($message->getReplyTo()),
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
}
