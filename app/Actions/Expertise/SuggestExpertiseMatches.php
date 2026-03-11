<?php

namespace App\Actions\Expertise;

use App\Models\Expertise;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SuggestExpertiseMatches
{
    /**
     * Find existing expertise records that are similar to the given names.
     *
     * @return Collection<int, array{expertise: Expertise, score: int, matched_on: string}>
     */
    public static function handle(string $nameEn = '', string $nameFr = '', int $maxSuggestions = 5, int $minimumScore = 50): Collection
    {
        $sourceNames = collect([
            'name_en' => $nameEn !== '' && $nameEn !== '0' ? self::normalize($nameEn) : null,
            'name_fr' => $nameFr !== '' && $nameFr !== '0' ? self::normalize($nameFr) : null,
        ])->filter();

        if ($sourceNames->isEmpty()) {
            return collect();
        }

        $searchTerms = collect();

        if ($nameEn !== '' && $nameEn !== '0') {
            $searchTerms = $searchTerms->merge(self::extractSearchTerms($nameEn));
        }

        if ($nameFr && $nameFr !== $nameEn) {
            $searchTerms = $searchTerms->merge(self::extractSearchTerms($nameFr));
        }

        if ($searchTerms->isEmpty()) {
            return collect();
        }

        $candidates = Expertise::query()
            ->where(function (\Illuminate\Contracts\Database\Query\Builder $query) use ($searchTerms): void {
                foreach ($searchTerms as $term) {
                    $query->orWhere('name_en', 'like', "%{$term}%")
                        ->orWhere('name_fr', 'like', "%{$term}%");
                }
            })
            ->get();

        $results = collect();

        foreach ($candidates as $candidate) {
            $bestScore = 0;
            $matchedOn = '';

            $targetNames = collect([
                'name_en' => self::normalize($candidate->name_en),
                'name_fr' => self::normalize($candidate->name_fr),
            ]);

            foreach ($sourceNames as $sourceField => $sourceName) {
                foreach ($targetNames as $targetField => $targetName) {
                    $score = self::calculateSimilarity($sourceName, $targetName);

                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $matchedOn = "{$sourceField} ↔ {$targetField}";
                    }
                }
            }

            if ($bestScore >= $minimumScore) {
                $results->push([
                    'expertise' => $candidate,
                    'score' => $bestScore,
                    'matched_on' => $matchedOn,
                ]);
            }
        }

        return $results->sortByDesc('score')->take($maxSuggestions)->values();
    }

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
