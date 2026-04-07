<?php

declare(strict_types=1);

namespace App\Http\Integrations\Orcid\Enums;

enum PeerReviewType: string
{
    case REVIEW = 'review';
    case EVALUATION = 'evaluation';
}
