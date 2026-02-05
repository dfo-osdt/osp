<?php

namespace App\Observers;

use App\Models\Publication;

class PublicationObserver
{
    /**
     * Handle the Publication "updated" event.
     *
     * Sync the accepted_on date to the related manuscript record if it exists.
     */
    public function updated(Publication $publication): void
    {
        // Only sync if the accepted_on date has changed
        if ($publication->wasChanged('accepted_on')) {
            $this->syncAcceptedDateToManuscript($publication);
        }
    }

    /**
     * Sync the publication's accepted_on date to the related manuscript record.
     */
    protected function syncAcceptedDateToManuscript(Publication $publication): void
    {
        // Only sync if the publication has a related manuscript record
        if ($publication->manuscript_record_id) {
            $manuscriptRecord = $publication->manuscriptRecord;

            if ($manuscriptRecord) {
                // Format the date properly for the manuscript record
                $acceptedOn = $publication->accepted_on?->format('Y-m-d');

                // Update without triggering events to prevent infinite loops
                $manuscriptRecord->updateQuietly([
                    'accepted_on' => $acceptedOn,
                ]);
            }
        }
    }
}
