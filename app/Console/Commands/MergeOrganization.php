<?php

namespace App\Console\Commands;

use App\Actions\Organizations\MergeOrganizations;
use App\Models\Organization;
use Illuminate\Console\Command;

use function Laravel\Prompts\search;
use function Laravel\Prompts\select;

class MergeOrganization extends Command
{
    protected $signature = 'osp:merge-organization';

    protected $description = 'Merge an unverified organization into a verified organization';

    public function handle(): ?int
    {
        $this->info('Merge Organization Tool');
        $this->newLine();

        $unverifiedOrgs = Organization::where('is_validated', false)->get();

        if ($unverifiedOrgs->isEmpty()) {
            $this->info('No unverified organizations found.');

            return null;
        }

        $this->info('Unverified Organizations:');
        $this->table(
            ['ID', 'Name (EN)', 'Name (FR)', 'ROR ID'],
            $unverifiedOrgs->map(fn ($org): array => [
                $org->id,
                $org->name_en,
                $org->name_fr,
                $org->ror_identifier ?? 'N/A',
            ])->toArray()
        );

        $sourceOrgId = select(
            label: 'Select the source organization to merge FROM:',
            options: $unverifiedOrgs->mapWithKeys(fn ($org): array => [
                $org->id => "{$org->name_en} (ID: {$org->id})",
            ])->toArray()
        );

        $sourceOrg = Organization::find($sourceOrgId);

        $this->newLine();
        $this->info("Selected source organization: {$sourceOrg->name_en}");

        $targetOrg = search(
            label: 'Search for verified target organization to merge INTO:',
            options: fn (string $value) => $value !== ''
                ? Organization::where('is_validated', true)
                    ->where(function ($query) use ($value): void {
                        $query->where('name_en', 'like', "%{$value}%")
                            ->orWhere('name_fr', 'like', "%{$value}%")
                            ->orWhere('ror_identifier', 'like', "%{$value}%");
                    })
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn ($org): array => [
                        $org->id => "{$org->name_en} ({$org->ror_identifier}) - ID: {$org->id}",
                    ])
                    ->toArray()
                : [],
            placeholder: 'Type to search verified organizations...',
        );

        $targetOrg = Organization::find($targetOrg);

        $this->newLine();
        $this->info("Source: {$sourceOrg->name_en} (ID: {$sourceOrg->id})");
        $this->info("Target: {$targetOrg->name_en} (ID: {$targetOrg->id})");
        $this->newLine();

        $relatedCounts = [
            'Authors' => $sourceOrg->authors()->count(),
            'Manuscript Authors' => $sourceOrg->manuscriptAuthors()->count(),
            'Author Employments' => \App\Models\AuthorEmployment::where('organization_id', $sourceOrg->id)->count(),
            'Publication Authors' => \App\Models\PublicationAuthor::where('organization_id', $sourceOrg->id)->count(),
            'Funders' => \App\Models\Funder::where('organization_id', $sourceOrg->id)->count(),
        ];

        $this->info('Related records that will be merged:');
        foreach ($relatedCounts as $type => $count) {
            if ($count > 0) {
                $this->line("  - {$type}: {$count}");
            }
        }

        if (! $this->confirm('Do you want to proceed with the merge?', false)) {
            $this->info('Merge cancelled.');

            return null;
        }

        try {
            MergeOrganizations::handle($sourceOrg, $targetOrg);
            $this->newLine();
            $this->info('✓ Organizations merged successfully!');
            $this->info("  {$sourceOrg->name_en} has been merged into {$targetOrg->name_en}");
        } catch (\Exception $e) {
            $this->error('Error merging organizations: '.$e->getMessage());

            return 1;
        }

        return 0;
    }
}
