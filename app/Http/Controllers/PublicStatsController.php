<?php

namespace App\Http\Controllers;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\PublicationStatus;
use App\Http\Resources\PublicAuthorResource;
use App\Models\Author;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PublicStatsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $stats = Cache::remember('public-stats', 86400, function () {
            $defaultOrg = Organization::getDefaultOrganization();

            return [
                'publications_count' => Publication::query()
                    ->where('status', PublicationStatus::PUBLISHED)
                    ->count(),
                'manuscripts_reviewed_count' => ManuscriptRecord::query()
                    ->whereIn('status', [
                        ManuscriptRecordStatus::REVIEWED,
                        ManuscriptRecordStatus::ACCEPTED,
                    ])
                    ->count(),
                'authors_count' => Author::query()->whereHas('publicationAuthors')->count(),
                'top_organizations' => Organization::query()
                    ->where('organizations.id', '!=', $defaultOrg->id)
                    ->whereNotNull('ror_identifier')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('publication_authors')
                            ->whereColumn('publication_authors.organization_id', 'organizations.id')
                            ->whereNull('publication_authors.deleted_at');
                    })
                    ->select(['organizations.id', 'name_en', 'name_fr', 'ror_identifier'])
                    ->selectSub(
                        PublicationAuthor::query()
                            ->whereColumn('publication_authors.organization_id', 'organizations.id')
                            ->whereNull('publication_authors.deleted_at')
                            ->select(DB::raw('count(distinct publication_id)')),
                        'publications_count'
                    )
                    ->orderByDesc('publications_count')
                    ->limit(5)
                    ->get(),
                'recent_publications' => Publication::query()
                    ->where('status', PublicationStatus::PUBLISHED)
                    ->whereNotNull('doi')
                    ->with(['journal:id,title', 'publicationAuthors.author:id,first_name,last_name,orcid,orcid_verified'])
                    ->latest('published_on')
                    ->limit(5)
                    ->get(['id', 'title', 'doi', 'published_on', 'journal_id'])
                    ->map(function (Publication $pub) {
                        $data = $pub->only(['id', 'title', 'doi', 'published_on']);
                        $data['journal'] = $pub->journal?->only(['id', 'title']);
                        $data['authors'] = $pub->publicationAuthors
                            ->filter(fn ($pa) => $pa->author !== null)
                            ->map(fn ($pa) => new PublicAuthorResource($pa->author))
                            ->values();

                        return $data;
                    }),
            ];
        });

        return response()->json($stats);
    }
}
