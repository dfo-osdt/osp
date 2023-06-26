<?php

namespace App\Enums;

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
    case APPROVED = 'approved';
    case WITHHELD = 'withheld';

    public function translate(string $locale = null): string
    {

        collect(['en', 'fr'])->contains($locale) ?: $locale = null;
        if($locale == null) $locale = app()->getLocale();

        return match ($locale) {
            'en' => match ($this->value) {
                'none' => 'No Decision',
                'approved' => 'Approved',
                'withheld' => 'Withheld',
            },
            'fr' => match ($this->value) {
                'none' => 'Aucune Decision',
                'approved' => 'Approuvé',
                'withheld' => 'Retenu',
            },
            default => $this->value,
        };

    }
}


