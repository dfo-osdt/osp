<?php

namespace App\Listeners;

use App\Events\ItemShared;
use App\Mail\ManuscriptRecordSharedMail;
use App\Models\ManuscriptRecord;
use App\Models\Shareable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ItemSharedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ItemShared $event): void
    {
        // find the shareable type and send appropriate notification
        $shareable = $event->shareableItem;

        switch ($shareable->shareable_type) {
            case ManuscriptRecord::class:
                Mail::queue(new ManuscriptRecordSharedMail($shareable));
                break;
            default:
                return;
        }
    }
}
