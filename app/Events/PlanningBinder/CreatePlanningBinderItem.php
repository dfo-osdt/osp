<?php

namespace App\Events\PlanningBinder;

use App\States\PlanningBinder\PlanningBinderItemState;
use Illuminate\Contracts\Container\BindingResolutionException;
use PHPUnit\Framework\Attributes\TestDox;
use Thunk\Verbs\Attributes\Autodiscovery\AppliesToState;
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
    ){
        $this->planning_binder_item_id = $planning_binder_item_id ?? snowflake_id();
    }

    public function validate(): bool
    {
        // make sure the polymorphic relation is valid and is a Publication or ManuscriptRecord
        switch ($this->item_model_type) {
            case 'App\Models\Publication':
                $this->assert(\App\Models\Publication::exists($this->item_model_id),
                    'Publication does not exist');
                return true;
            case 'App\Models\ManuscriptRecord':
                $this->assert(\App\Models\ManuscriptRecord::exists($this->item_model_id),
                    'Manuscript record form does not exist');
                return true;
            default:
                throw new \Exception('Invalid item model type');
        }

        // make sure the user exists
        $this->assert(\App\Models\User::exists($this->user_id), 'User does not exist');

    }



    public function fired(){
        // send a notification here
    }

    public function handle()
    {
        // Real G's move in silence like Lasagna - Lil Wayne
    }
}
