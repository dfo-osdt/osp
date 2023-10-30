<?php

namespace App\Traits;

use App\Models\Expertise;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasExpertises
{
    /**
     * Get the Expertises that belong to this HasExpertises.
     */
    public function expertises(): MorphToMany
    {
        return $this->morphToMany(Expertise::class, 'expertiseable');
    }
}
