<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Where an item (peer review, employment, later works) stands with ORCID, worked
 * out from what OSP stores. Failed syncs are not recorded, so they show as pending.
 *
 * Not eligible: cannot go to ORCID (e.g. not published, no DOI, author has no user account)
 * Not connected: the author has no ORCID connection OSP can write with
 * Not decided: the author has not said whether they want it on ORCID
 * Declined: the author does not want it on ORCID
 * Pending: wanted, but not on ORCID yet or out of date there
 * On ORCID: on the author's ORCID record and up to date
 * Removing: on ORCID but should no longer be, waiting for the delete
 */
enum OrcidSyncStatus: string implements HasColor, HasLabel
{
    case NOT_ELIGIBLE = 'not_eligible';
    case NOT_CONNECTED = 'not_connected';
    case NOT_DECIDED = 'not_decided';
    case DECLINED = 'declined';
    case PENDING = 'pending';
    case ON_ORCID = 'on_orcid';
    case REMOVING = 'removing';

    public function getLabel(): string
    {
        return __("orcid.sync_status.{$this->value}");
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ON_ORCID => 'success',
            self::PENDING, self::REMOVING => 'warning',
            self::NOT_CONNECTED => 'danger',
            self::NOT_ELIGIBLE, self::NOT_DECIDED, self::DECLINED => 'gray',
        };
    }
}
