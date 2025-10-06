<?php

namespace App\Actions\Organizations;

use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class MergeOrganizations
{
    public static function handle(Organization $sourceOrganization, Organization $targetOrganization): bool
    {
        if ($sourceOrganization->id === $targetOrganization->id) {
            throw new \InvalidArgumentException('Source and target organizations cannot be the same.');
        }

        return DB::transaction(function () use ($sourceOrganization, $targetOrganization) {
            DB::table('authors')
                ->where('organization_id', $sourceOrganization->id)
                ->update(['organization_id' => $targetOrganization->id]);

            DB::table('manuscript_authors')
                ->where('organization_id', $sourceOrganization->id)
                ->update(['organization_id' => $targetOrganization->id]);

            DB::table('publication_authors')
                ->where('organization_id', $sourceOrganization->id)
                ->update(['organization_id' => $targetOrganization->id]);

            DB::table('author_employments')
                ->where('organization_id', $sourceOrganization->id)
                ->update(['organization_id' => $targetOrganization->id]);

            DB::table('funders')
                ->where('organization_id', $sourceOrganization->id)
                ->update(['organization_id' => $targetOrganization->id]);

            return $sourceOrganization->delete();
        });
    }
}
