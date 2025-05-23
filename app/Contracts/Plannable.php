<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface Plannable
{
    /**
     * Get the PlanningBinderItems that are associated with this Plannable.
     */
    public function planningBinderItem(): MorphOne;
}
