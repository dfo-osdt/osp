<?php

namespace App\Traits;

use App\Models\PlanningBinderItem;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait PlannableTrait
{
    /**
     * Get the PlanningBinderItems that are associated with this Plannable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne<\App\Models\PlanningBinderItem, $this>
     */
    public function planningBinderItem(): MorphOne
    {
        return $this->morphOne(PlanningBinderItem::class, 'plannable');
    }
}
