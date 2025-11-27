<?php

namespace App\Events;

use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManuscriptRecordToReviewEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public ManuscriptRecord $manuscriptRecord, public User $divisionManagerUser) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): \Illuminate\Broadcasting\PrivateChannel
    {
        return new PrivateChannel('channel-name');
    }
}
