<?php

namespace App\Enums;

use Illuminate\Support\Facades\App;

/**
 * Enums that describe the decision taken on a management review step.
 *
 * None: No decision has been made.
 * Flagged: The manuscript record is flagged by the reviewer.
 * Approved: The manuscript record is approved by the reviewer.
 * Withheld: The manuscript record is withheld by the reviewer.
 * Withdrawn: The manuscript record is withdrawn by the author following a flagged status.
 */
enum ManagementReviewStepDecision: string
{
    case NONE = 'none';
    case FLAGGED = 'flagged';
    case APPROVED = 'approved';
    case WITHHELD = 'withheld';
    case WITHDRAWN = 'withdrawn';

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
                'withdrawn' => 'Withdrawn',
            },
            'fr' => match ($this->value) {
                'none' => 'Aucune Decision',
                'flagged' => 'Signalé',
                'approved' => 'Approuvé',
                'withheld' => 'Retenu',
                'withdrawn' => 'Retiré',
            },
            default => $this->value,
        };
    }
}
