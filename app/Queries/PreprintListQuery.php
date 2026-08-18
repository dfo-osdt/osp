<?php

namespace App\Queries;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\ManuscriptRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/** @extends QueryBuilder<ManuscriptRecord> */
class PreprintListQuery extends QueryBuilder
{
    public function __construct(Builder|Relation|null $subject = null, ?Request $request = null)
    {
        parent::__construct(
            $subject ?? ManuscriptRecord::query()
                ->where('type', ManuscriptRecordType::PREPRINT)
                ->where('status', ManuscriptRecordStatus::ACCEPTED)
                ->with([
                    'manuscriptAuthors.author',
                    'manuscriptAuthors.organization',
                    'managementReviewSteps',
                    'shareables',
                    'region',
                ]),
            $request,
        );

        $this
            ->defaultSort('accepted_on')
            ->allowedSorts(
                'title',
            )
            ->allowedFilters(
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::partial('title'),
                AllowedFilter::exact('manuscriptAuthors.author_id'),
            );
    }
}
