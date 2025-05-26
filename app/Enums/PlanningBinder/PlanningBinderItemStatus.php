<?php

namespace App\Enums\PlanningBinder;

enum PlanningBinderItemStatus: string
{
    case FLAGGED = 'flagged';
    case PENDING = 'pending';
    case READY = 'ready';
    case POSTED = 'posted';
}
