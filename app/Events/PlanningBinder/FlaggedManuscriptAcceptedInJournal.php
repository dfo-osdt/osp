<?php

namespace App\Events\PlanningBinder;

use App\Enums\ManuscriptRecordType;
use App\Enums\PlanningBinder\PlanningBinderItemStatus;
use App\Mail\PlanningBinder\FlaggedManuscriptAcceptedInJournalMail;
use App\Models\ManuscriptRecord;
use App\Models\PlanningBinderItem;
use App\Models\Publication;
use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Support\Facades\Mail;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class FlaggedManuscriptAcceptedInJournal extends Event
{
    #[StateId(PlanningBinderItemState::class)]
    public int $planning_binder_item_id;

    public function __construct(
        public int $user_id,
        int $planning_binder_item_id,
    ) {
        $this->planning_binder_item_id = $planning_binder_item_id;
    }

    public function validate(PlanningBinderItemState $state): bool
    {
        // should be ready
        if ($state->status !== PlanningBinderItemStatus::FLAGGED) {
            return false;
        }

        // cant be of type preprint
        if ($state->manuscript_record_type === ManuscriptRecordType::PREPRINT) {
            return false;
        }

        // mrf needs a publication id
        $mrf = ManuscriptRecord::where('ulid', $state->manuscript_record_ulid)->firstOrFail();
        if ($mrf->publication->id === null) {
            return false;
        }

        return true;
    }

    public function apply(PlanningBinderItemState $state)
    {
        // set the status to flagged
        $state->status = PlanningBinderItemStatus::READY;

        $mrf = ManuscriptRecord::where('ulid', $state->manuscript_record_ulid)->firstOrFail();
        $state->publication_id = $mrf->publication->id;

    }

    public function fired(PlanningBinderItemState $state): bool
    {
        Mail::queue(new FlaggedManuscriptAcceptedInJournalMail(
            planningBinderItemState: $state
        ));

        return true;

    }

    public function handle(PlanningBinderItemState $state)
    {

        $item = PlanningBinderItem::findOrFail($state->id);
        $item->status = $state->status;
        $item->plannable()->associate(Publication::findOrFail($state->publication_id));

    }
}
