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

    public function label(string $locale = 'en'): string
    {
        if ($locale === 'fr') {
            return match ($this) {
                self::PRIMARY => 'Publication tierce partie',
                self::SECONDARY => 'Publication du MPO',
                self::PREPRINT => 'Prépublication',
            };
        }

        return match ($this) {
            self::PRIMARY => 'Third-Party Publication',
            self::SECONDARY => 'DFO Publication',
            self::PREPRINT => 'Preprint',
        };
    }
}
