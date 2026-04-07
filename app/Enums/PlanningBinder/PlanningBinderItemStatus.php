<?php

declare(strict_types=1);

namespace App\Enums\PlanningBinder;

enum PlanningBinderItemStatus: string
{
    case FLAGGED = 'flagged';
    case PENDING = 'pending';
    case READY = 'ready';
    case POSTED = 'posted';
}
