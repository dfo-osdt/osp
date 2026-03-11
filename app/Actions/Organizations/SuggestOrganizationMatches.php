<?php

namespace App\Actions\Organizations;

use App\Models\Organization;
use App\Traits\ComparesStringSimilarity;
use Illuminate\Support\Collection;

class SuggestOrganizationMatches
{
    use ComparesStringSimilarity;

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

        $sourceNames = collect([
            'name_en' => self::normalize($org->name_en),
            'name_fr' => self::normalize($org->name_fr),
            'abbr_en' => $org->abbr_en ? self::normalize($org->abbr_en) : null,
            'abbr_fr' => $org->abbr_fr ? self::normalize($org->abbr_fr) : null,
        ])->filter();

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

            $targetNames = collect([
                'name_en' => self::normalize($candidate->name_en),
                'name_fr' => self::normalize($candidate->name_fr),
            ]);

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
}
