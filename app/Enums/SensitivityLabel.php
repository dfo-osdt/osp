<?php

namespace App\Enums;

use Illuminate\Support\Facades\App;

enum SensitivityLabel: string
{
    case Unclassified = 'Unclassified';
    case ProtectedA = 'Protected A';

    public function translate(?string $locale = null): string
    {

        collect(['en', 'fr'])->contains($locale) ?: $locale = null;
        if ($locale == null) {
            $locale = App::getLocale();
        }

        return match ($locale) {
            'en' => match ($this->value) {
                'Unclassified' => 'Unclassified',
                'Protected A' => 'Protected A',
            },
            'fr' => match ($this->value) {
                'Unclassified' => 'Non classifié',
                'Protected A' => 'Protégé A',
            },
            default => $this->value,
        };
    }
}
