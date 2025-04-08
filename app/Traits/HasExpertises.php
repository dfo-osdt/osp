<?php

namespace App\Traits;

use App\Models\Expertise;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasExpertises
{
    /**
     * Get the Expertises that belong to this HasExpertises.
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany<\App\Models\Expertise, $this>
     */
    public function expertises(): MorphToMany
    {
        return $this->morphToMany(Expertise::class, 'expertiseable');
    }
}
