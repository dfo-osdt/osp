<?php

namespace App\Enums;

use Illuminate\Support\Facades\App;

/**
 * Enums that describe the decision taken on a management review step.
 *
 * None: No decision has been made.
 * Revision: The manuscript record is flagged by the reviewer.
 * Complete: The manuscript record is in alignment with the poilicy.
 * Withdrawn: The manuscript record is withdrawn by the author following a flagged status.
 */
enum ManagementReviewStepDecision: string
{
    case NONE = 'none';
    case REVISION = 'revision';
    case COMPLETE = 'complete';
    case WITHDRAWN = 'withdrawn';

    public function translate(?string $locale = null): string
    {

        collect(['en', 'fr'])->contains($locale) ?: $locale = null;
        if ($locale == null) {
            $locale = App::getLocale();
        }

        return match ($locale) {
            'en' => match ($this->value) {
                'none' => 'No Decision',
                'revision' => 'Revision requested',
                'complete' => 'Complete',
                'withdrawn' => 'Withdrawn',
            },
            'fr' => match ($this->value) {
                'none' => 'Aucune Decision',
                'revision' => 'Révision demandée',
                'complete' => 'Complète',
                'withdrawn' => 'Retiré',
            },
            default => $this->value,
        };
    }
}
