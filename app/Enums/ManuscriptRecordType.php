<?php

namespace App\Enums;

/**
 * Enums that describe the available types of manuscript record,
 * currently there are only two types: primary and secondary.
 */
enum ManuscriptRecordType: string
{
    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
}
