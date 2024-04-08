<?php

namespace App\Enums;

/**
 * Enums that describe the state in which a management review step can be.
 *
 * Pending: A decision on this management review step is pending.
 * Reassign: No decision taken, this management review step was reassigned to another user. i.e. completed with no decision.
 * On Hold: This management review step is back in the hands of the applicant and on hold until the applicant
 *          provides more information or addresses management review comments.
 * Completed: This management review step is completed.
 */
enum ManagementReviewStepStatus: string
{
    case PENDING = 'pending';
    case REASSIGN = 'reassign';
    case ON_HOLD = 'on_hold';
    case COMPLETED = 'completed';
}
