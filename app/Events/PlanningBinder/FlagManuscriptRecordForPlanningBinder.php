<?php

namespace App\Events\PlanningBinder;

use App\Enums\PlanningBinder\PlanningBinderItemStatus;
use App\Mail\PlanningBinder\ManuscriptFlaggedForPlanningBinder;
use App\Models\ManuscriptRecord;
use App\Models\User;
use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Support\Facades\Mail;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class FlagManuscriptRecordForPlanningBinder extends Event
{
    #[StateId(PlanningBinderItemState::class)]
    public int $planning_binder_item_id;

    public function __construct(
        public int $user_id,
        public string $manuscript_record_ulid,
        $planning_binder_item_id = null,
    ) {
        $this->planning_binder_item_id = $planning_binder_item_id ?? snowflake_id();
    }

    public function validate(): bool
    {
        return true;

    }

    public function apply(PlanningBinderItemState $state)
    {
        // set the status to flagged
        $state->status = PlanningBinderItemStatus::FLAGGED;

        $mrf = ManuscriptRecord::where('ulid', $this->manuscript_record_ulid)->firstOrFail();

        // set the type to manuscript record
        $state->manuscript_record_type = $mrf->type;

        // set the ulid to the manuscript record ulid
        $state->manuscript_record_ulid = $this->manuscript_record_ulid;

    }

    public function fired(PlanningBinderItemState $state)
    {
        // send notification to the user
        Mail::queue(new ManuscriptFlaggedForPlanningBinder(
            User::findOrFail($this->user_id),
            $state
        ));

    }

    public function handle()
    {
        // Real G's move in silence like Lasagna - Lil Wayne
    }
}
