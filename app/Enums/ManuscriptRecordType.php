<?php

namespace App\Enums;

/**
 * Enums that describe the available types of manuscript record
 */
enum ManuscriptRecordType: string
{
    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case PREPRINT = 'preprint';
}
