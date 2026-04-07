<?php

declare(strict_types=1);

namespace App\Http\Integrations\Orcid\Enums;

enum PeerReviewRole: string
{
    case CHAIR = 'chair';
    case EDITOR = 'editor';
    case MEMBER = 'member';
    case ORGANIZER = 'organizer';
    case REVIEWER = 'reviewer';
}
