<?php

namespace App\Console\Commands;

use App\Actions\Organizations\MergeOrganizations;
use App\Actions\Organizations\SuggestOrganizationMatches;
use App\Models\Organization;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

class SuggestOrganizationRORMatches extends Command
{
    protected $signature = 'osp:suggest-ror-matches
                            {--min-score=50 : Minimum similarity score (0-100)}
                            {--max-suggestions=5 : Maximum suggestions per organization}
                            {--merge : Interactively merge matched organizations}';

    protected $description = 'Suggest ROR matches for manually created organizations';

    public function handle(): int
    {
        $minScore = (int) $this->option('min-score');
        $maxSuggestions = (int) $this->option('max-suggestions');
        $interactive = $this->option('merge');

        $unvalidatedCount = Organization::query()
            ->where('is_validated', false)
            ->whereNull('ror_identifier')
            ->count();

        if ($unvalidatedCount === 0) {
            $this->info('No unvalidated organizations found.');

            return 0;
        }

        $this->info("Found {$unvalidatedCount} unvalidated organization(s). Searching for ROR matches...");
        $this->newLine();

        $results = SuggestOrganizationMatches::handle($maxSuggestions, $minScore);

        $matchFound = false;

        foreach ($results as $result) {
            /** @var Organization $source */
            $source = $result['source'];
            $matches = $result['matches'];

            $this->components->twoColumnDetail(
                "<fg=white;options=bold>{$source->name_en}</>",
                "ID: {$source->id}".($source->country_code ? " | {$source->country_code}" : '')
            );

            if ($source->name_fr !== $source->name_en) {
                $this->line("  FR: {$source->name_fr}");
            }

            if ($matches->isEmpty()) {
                $this->line('  <fg=yellow>No matches found.</>');
                $this->newLine();

                continue;
            }

            $matchFound = true;

            foreach ($matches as $match) {
                /** @var Organization $candidate */
                $candidate = $match['organization'];
                $score = $match['score'];
                $matchedOn = $match['matched_on'];

                $scoreColor = $score >= 90 ? 'green' : ($score >= 70 ? 'yellow' : 'red');

                $this->line(
                    "  <fg={$scoreColor}>[{$score}%]</> {$candidate->name_en}"
                    ." <fg=gray>({$candidate->ror_identifier})</>"
                );

                if ($candidate->name_fr !== $candidate->name_en) {
                    $this->line("        FR: {$candidate->name_fr}");
                }
            }

            if ($interactive && $matches->isNotEmpty()) {
                $this->newLine();
                $this->handleInteractiveMerge($source, $matches);
            }

            $this->newLine();
        }

        if (! $matchFound) {
            $this->info('No matches found for any unvalidated organizations.');
        }

        return 0;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array{organization: Organization, score: int, matched_on: string}>  $matches
     */
    private function handleInteractiveMerge(Organization $source, $matches): void
    {
        $options = $matches->mapWithKeys(fn ($match): array => [
            $match['organization']->id => "[{$match['score']}%] {$match['organization']->name_en} ({$match['organization']->ror_identifier})",
        ])->all();
        $options['skip'] = 'Skip this organization';

        $selectedId = select(
            label: "Merge \"{$source->name_en}\" into:",
            options: $options,
        );

        if ($selectedId === 'skip') {
            $this->line('  Skipped.');

            return;
        }

        $target = Organization::query()->find($selectedId);

        if (! confirm("Merge \"{$source->name_en}\" into \"{$target->name_en}\"?", false)) {
            $this->line('  Skipped.');

            return;
        }

        try {
            MergeOrganizations::handle($source, $target);
            $this->info("  Merged successfully into {$target->name_en}");
        } catch (\Exception $e) {
            $this->error("  Merge failed: {$e->getMessage()}");
        }
    }
}
