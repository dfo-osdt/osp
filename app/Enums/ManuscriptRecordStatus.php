<?php

namespace App\Enums;

/**
 * Enums that describe the state in which a manuscript record can be.
 *
 * Draft: The manuscript record is in draft state - initially created by the user.
 * In Review: The manuscript record is in review state - sent for internal review by the user.
 * Reviewed: The manuscript record is reviewed by management.
 * Submitted: The manuscript record is submitted to the journal for review.
 * Accepted: The manuscript record is accepted by the journal.
 * Withdrawn: The manuscript record is withdrawn by the user either at the in-review or submitted stage.
 */
enum ManuscriptRecordStatus: string
{
    case DRAFT = 'draft';
    case IN_REVIEW = 'in_review';
    case REVIEWED = 'reviewed';
    case SUBMITTED = 'submitted';
    case ACCEPTED = 'accepted';
    case WITHDRAWN = 'withdrawn';
}
