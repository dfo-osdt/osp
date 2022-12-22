<?php

namespace App\Traits;

use App\Models\FundingSource;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait FundableTrait
{
    /**
     * Get the FundingSources that fund this Fundable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fundingSources(): MorphMany
    {
        return $this->morphMany(FundingSource::class, 'fundable');
    }
}
