<?php

namespace App\Http\Integrations\Orcid\Enums;

enum PeerReviewType: string
{
    case REVIEW = 'review';
    case EVALUATION = 'evaluation';
}
