<?php

namespace App\Enums;

/**
 * Enums that describes the state of a publication.
 *
 * Accepted: The publication is accepted by the journal.
 * Published: The publication is published by the journal.
 */
enum PublicationStatus: string
{
    case ACCEPTED = 'accepted';
    case PUBLISHED = 'published';
}
