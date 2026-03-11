<?php

namespace App\Actions\Expertise;

use App\Models\Expertise;
use App\Traits\ComparesStringSimilarity;
use Illuminate\Support\Collection;

class SuggestExpertiseMatches
{
    use ComparesStringSimilarity;

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
}
