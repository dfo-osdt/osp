<?php

namespace App\Actions\Organizations;

use App\Models\Organization;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SuggestOrganizationMatches
{
    /**
     * Find potential ROR matches for all unvalidated organizations.
     *
     * @return Collection<int, array{source: Organization, matches: Collection}>
     */
    public static function handle(int $maxSuggestions = 5, int $minimumScore = 50): Collection
    {
        $unvalidatedOrgs = Organization::query()
            ->where('is_validated', false)
            ->whereNull('ror_identifier')
            ->get();

        if ($unvalidatedOrgs->isEmpty()) {
            return collect();
        }

        return $unvalidatedOrgs->map(function (Organization $org) use ($maxSuggestions, $minimumScore): array {
            $matches = self::findMatchesFor($org, $maxSuggestions, $minimumScore);

            return [
                'source' => $org,
                'matches' => $matches,
            ];
        });
    }

    /**
     * Find potential ROR matches for a single unvalidated organization.
     *
     * @return Collection<int, array{organization: Organization, score: int, matched_on: string}>
     */
    public static function findMatchesFor(Organization $org, int $maxSuggestions = 5, int $minimumScore = 50): Collection
    {
        $candidates = collect();

        // Normalize source names for comparison
        $sourceNames = collect([
            'name_en' => self::normalize($org->name_en),
            'name_fr' => self::normalize($org->name_fr),
            'abbr_en' => $org->abbr_en ? self::normalize($org->abbr_en) : null,
            'abbr_fr' => $org->abbr_fr ? self::normalize($org->abbr_fr) : null,
        ])->filter();

        // Query candidates using LIKE for each source name to narrow the search
        $candidateOrgs = Organization::query()
            ->where('is_validated', true)
            ->whereNotNull('ror_identifier')
            ->where(function (\Illuminate\Contracts\Database\Query\Builder $query) use ($org): void {
                $searchTerms = self::extractSearchTerms($org->name_en);

                foreach ($searchTerms as $term) {
                    $query->orWhere('name_en', 'like', "%{$term}%")
                        ->orWhere('name_fr', 'like', "%{$term}%")
                        ->orWhere('ror_names', 'like', "%{$term}%");
                }

                if ($org->name_fr !== $org->name_en) {
                    $frenchTerms = self::extractSearchTerms($org->name_fr);
                    foreach ($frenchTerms as $term) {
                        $query->orWhere('name_en', 'like', "%{$term}%")
                            ->orWhere('name_fr', 'like', "%{$term}%")
                            ->orWhere('ror_names', 'like', "%{$term}%");
                    }
                }
            })
            ->get();

        foreach ($candidateOrgs as $candidate) {
            $bestScore = 0;
            $matchedOn = '';

            // Compare against candidate's main names
            $targetNames = collect([
                'name_en' => self::normalize($candidate->name_en),
                'name_fr' => self::normalize($candidate->name_fr),
            ]);

            // Also compare against all ROR name variants
            $rorNames = collect(json_decode((string) $candidate->ror_names, true) ?? [])
                ->pluck('value')
                ->map(fn (string $value): string => self::normalize($value));

            $allTargetNames = $targetNames->merge($rorNames->mapWithKeys(fn ($name, $key): array => ["ror_{$key}" => $name]));

            foreach ($sourceNames as $sourceField => $sourceName) {
                foreach ($allTargetNames as $targetField => $targetName) {
                    $score = self::calculateSimilarity($sourceName, $targetName);

                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $matchedOn = "{$sourceField} ↔ {$targetField}";
                    }
                }
            }

            if ($bestScore >= $minimumScore) {
                $candidates->push([
                    'organization' => $candidate,
                    'score' => $bestScore,
                    'matched_on' => $matchedOn,
                ]);
            }
        }

        return $candidates->sortByDesc('score')->take($maxSuggestions)->values();
    }

    /**
     * Normalize a string for comparison.
     */
    private static function normalize(string $value): string
    {
        $value = Str::lower($value);
        $value = Str::ascii($value);
        // Remove common prefixes/suffixes that reduce match quality
        $value = (string) preg_replace('/\b(the|of|de|du|des|la|le|les|l\'|d\'|und|and|et)\b/', '', $value);
        $value = (string) preg_replace('/[^\w\s]/', '', $value);
        $value = (string) preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }

    /**
     * Extract meaningful search terms from an organization name.
     *
     * @return array<int, string>
     */
    private static function extractSearchTerms(string $name): array
    {
        $stopWords = ['the', 'of', 'de', 'du', 'des', 'la', 'le', 'les', 'and', 'et', 'und', 'for', 'pour'];

        return collect(explode(' ', $name))
            ->map(fn (string $word): string => trim($word, '.,;:\'"-()'))
            ->filter(fn (string $word): bool => Str::length($word) >= 3 && ! in_array(Str::lower($word), $stopWords))
            ->values()
            ->all();
    }

    /**
     * Calculate similarity score between two normalized strings (0-100).
     */
    private static function calculateSimilarity(string $a, string $b): int
    {
        // Exact match
        if ($a === $b) {
            return 100;
        }

        // Use similar_text percentage
        similar_text($a, $b, $percent);

        // Boost score if one string contains the other
        if (Str::contains($a, $b) || Str::contains($b, $a)) {
            $percent = max($percent, 80);
        }

        return (int) round($percent);
    }
}
