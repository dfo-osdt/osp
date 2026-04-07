<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Fundable
{
    /**
     * Get the FundingSources that fund this Fundable.
     */
    public function fundingSources(): MorphMany;
}
