<?php

namespace App\Events\PlanningBinder;

use App\States\PlanningBinder\PlanningBinderItemState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class CreatePlanningBinderItem extends Event
{
    #[StateId(PlanningBinderItemState::class)]
    public int $planning_binder_item_id;

    public function __construct(
        public int $user_id,
        public int $item_model_type,
        public int $item_model_id,
        $planning_binder_item_id = null,
        //todo; think about the state - media lines, notes, etc.
    ) {
        $this->planning_binder_item_id = $planning_binder_item_id ?? snowflake_id();
    }

    public function validate(): bool
    {
        // Validate the polymorphic relation and ensure it's a Publication or ManuscriptRecord
        $validTypes = [
            \App\Models\Publication::class,
            \App\Models\ManuscriptRecord::class,
        ];

        $this->assert(in_array($this->item_model_type, $validTypes, true), 'Invalid item model type');

        $modelClass = $this->item_model_type;
        $this->assert($modelClass::exists($this->item_model_id), class_basename($modelClass) . ' does not exist');

        return true;

        // make sure the user exists
        $this->assert(\App\Models\User::exists($this->user_id), 'User does not exist');

    }

    public function fired()
    {
        // send a notification here
    }

    public function handle()
    {
        // Real G's move in silence like Lasagna - Lil Wayne
    }
}
