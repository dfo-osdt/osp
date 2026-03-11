<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait ComparesStringSimilarity
{
    /**
     * Normalize a string for comparison.
     */
    private static function normalize(string $value): string
    {
        $value = Str::lower($value);
        $value = Str::ascii($value);
        $value = (string) preg_replace('/\b(the|of|de|du|des|la|le|les|l\'|d\'|und|and|et)\b/', '', $value);
        $value = (string) preg_replace('/[^\w\s]/', '', $value);
        $value = (string) preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }

    /**
     * Extract meaningful search terms from a name.
     *
     * @return Collection<int, string>
     */
    private static function extractSearchTerms(string $name): Collection
    {
        $stopWords = ['the', 'of', 'de', 'du', 'des', 'la', 'le', 'les', 'and', 'et', 'und', 'for', 'pour'];

        return collect(explode(' ', $name))
            ->map(fn (string $word): string => trim($word, '.,;:\'"-()'))
            ->filter(fn (string $word): bool => Str::length($word) >= 3 && ! in_array(Str::lower($word), $stopWords))
            ->values();
    }

    /**
     * Calculate similarity score between two normalized strings (0-100).
     */
    private static function calculateSimilarity(string $a, string $b): int
    {
        if ($a === $b) {
            return 100;
        }

        similar_text($a, $b, $percent);

        if (Str::contains($a, $b) || Str::contains($b, $a)) {
            $percent = max($percent, 80);
        }

        return (int) round($percent);
    }
}
