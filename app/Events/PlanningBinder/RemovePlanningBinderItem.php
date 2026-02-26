<?php

namespace App\Events\PlanningBinder;

use App\Models\PlanningBinderItem;
use App\States\PlanningBinder\PlanningBinderItemState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class RemovePlanningBinderItem extends Event
{
    #[StateId(PlanningBinderItemState::class)]
    public int $planning_binder_item_id;

    public function validate(): bool
    {
        return PlanningBinderItem::query()->where('id', $this->planning_binder_item_id)->exists();
    }

    public function apply(PlanningBinderItemState $state): void
    {
        // No state change needed — item is being removed.
    }

    public function handle(): void
    {
        PlanningBinderItem::query()->where('id', $this->planning_binder_item_id)->delete();
    }
}
