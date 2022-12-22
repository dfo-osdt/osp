<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Fundable
{
    /**
     * Get the FundingSources that fund this Fundable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fundingSources(): MorphMany;
}
