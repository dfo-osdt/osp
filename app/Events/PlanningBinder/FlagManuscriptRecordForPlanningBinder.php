<?php

namespace App\Events\PlanningBinder;

use App\Enums\ManuscriptRecordStatus;
use App\Mail\PlanningBinder\ItemFlaggedForPlanningBinder;
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
        public User $user,
        public ManuscriptRecord $manuscript_record,
        $planning_binder_item_id = null,
    ) {
        $this->planning_binder_item_id = $planning_binder_item_id ?? snowflake_id();
    }

    public function validate(): bool
    {
        // the manuscript record must have passed the management review
        $this->assert(
            $this->manuscript_record->status === ManuscriptRecordStatus::REVIEWED,
            'The manuscript record must have passed the management review'
        );

        return true;

    }

    public function fired()
    {
        // send notification to the user
        Mail::queue(new ItemFlaggedForPlanningBinder(
            $this->user,
            $this->manuscript_record,
        ));

    }

    public function handle()
    {
        // Real G's move in silence like Lasagna - Lil Wayne
    }
}
