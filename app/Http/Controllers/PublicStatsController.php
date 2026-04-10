<?php

namespace App\Http\Controllers;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\PublicationStatus;
use App\Http\Resources\PublicAuthorResource;
use App\Models\Author;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use App\Models\Publication;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PublicStatsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $stats = Cache::remember('public-stats', 86400, function (): array { // @phpstan-ignore argument.type
            $defaultOrg = Organization::getDefaultOrganization();

            return [ // @phpstan-ignore return.type
                'publications_count' => Publication::query()
                    ->where('status', PublicationStatus::PUBLISHED)
                    ->count(),
                'manuscripts_reviewed_count' => ManuscriptRecord::query()
                    ->whereIn('status', [
                        ManuscriptRecordStatus::REVIEWED,
                        ManuscriptRecordStatus::ACCEPTED,
                    ])
                    ->count(),
                'authors_count' => Author::query()
                    ->where('organization_id', $defaultOrg->id)
                    ->whereHas('manuscriptAuthors')
                    ->count(),
                'external_authors_count' => Author::query()
                    ->where('organization_id', '!=', $defaultOrg->id)
                    ->whereHas('manuscriptAuthors')
                    ->count(),
                'orcid_connected_count' => Author::query()
                    ->where('orcid_verified', true)
                    ->count(),
                'external_organizations' => Organization::query()
                    ->where('organizations.id', '!=', $defaultOrg->id)
                    ->whereNotNull('ror_identifier')
                    ->whereExists(function ($query): void {
                        $query->select(DB::raw(1))
                            ->from('manuscript_authors')
                            ->whereColumn('manuscript_authors.organization_id', 'organizations.id');
                    })
                    ->select(['organizations.id', 'name_en', 'name_fr', 'ror_identifier'])
                    ->inRandomOrder()
                    ->limit(5)
                    ->get()
                    ->toArray(),
                'recent_publications' => Publication::query()
                    ->where('status', PublicationStatus::PUBLISHED)
                    ->whereNotNull('doi')
                    ->with(['journal:id,title', 'publicationAuthors.author:id,first_name,last_name,orcid,orcid_verified'])
                    ->latest('published_on')
                    ->limit(5)
                    ->get(['id', 'title', 'doi', 'published_on', 'journal_id'])
                    ->map(fn (Publication $pub): array => [
                        'id' => $pub->id,
                        'title' => $pub->title,
                        'doi' => $pub->doi,
                        'published_on' => $pub->published_on,
                        'journal' => $pub->journal->only(['id', 'title']),
                        'authors' => $pub->publicationAuthors
                            ->map(fn ($pa): PublicAuthorResource => new PublicAuthorResource($pa->author))
                            ->values()
                            ->all(),
                    ]),
            ];
        });

        return response()->json($stats);
    }
}
