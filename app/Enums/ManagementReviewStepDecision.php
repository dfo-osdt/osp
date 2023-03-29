<?php

namespace App\Enums;

/**
 * Enums that describe the decision taken on a management review step.
 *
 * None: No decision has been made.
 * Approved: The manuscript record is approved by the reviewer.
 * Withheld: The manuscript record is withheld by the reviewer.
 */
enum ManagementReviewStepDecision: string
{
    case NONE = 'none';
    case APPROVED = 'approved';
    case WITHHELD = 'withheld';
}
