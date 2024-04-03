<?php

namespace App\Enums;

use Illuminate\Support\Facades\App;

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
    case FLAGGED = 'flagged';
    case APPROVED = 'approved';
    case WITHHELD = 'withheld';

    public function translate(string $locale = null): string
    {

        collect(['en', 'fr'])->contains($locale) ?: $locale = null;
        if ($locale == null) {
            $locale = App::getLocale();
        }

        return match ($locale) {
            'en' => match ($this->value) {
                'none' => 'No Decision',
                'flagged' => 'Flagged',
                'approved' => 'Approved',
                'withheld' => 'Withheld',
            },
            'fr' => match ($this->value) {
                'none' => 'Aucune Decision',
                'flagged' => 'Signalé',
                'approved' => 'Approuvé',
                'withheld' => 'Retenu',
            },
            default => $this->value,
        };

    }
}
