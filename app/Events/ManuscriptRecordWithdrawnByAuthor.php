<?php

namespace App\Events;

use App\Models\ManuscriptRecord;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManuscriptRecordWithdrawnByAuthor
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public ManuscriptRecord $manuscriptRecord) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): \Illuminate\Broadcasting\PrivateChannel
    {
        return new PrivateChannel('channel-name');
    }
}
