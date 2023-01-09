<?php

namespace App\Enums;

/**
 * Enums that describe the state in which a management review step can be.
 *
 * Pending: A decision on this management review step is pending.
 * Reassign: No decision taken, this management review step was reassigned to another user. i.e. completed with no decision.
 * Completed: This management review step is completed.
 */
enum ManagementReviewStepStatus: string
{
    case PENDING = 'pending';
    case REASSIGN = 'reassign';
    case COMPLETED = 'completed';
}
