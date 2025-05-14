<?php

namespace App\Queries;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\ManuscriptRecord;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PreprintListQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(
            ManuscriptRecord::query()
                ->where('type', ManuscriptRecordType::PREPRINT)
                ->where('status', ManuscriptRecordStatus::ACCEPTED)
                ->with([
                    'manuscriptAuthors.author',
                    'manuscriptAuthors.organization',
                    'managementReviewSteps',
                    'shareables'
                    ])

        );

        $this
            ->defaultSort('accepted_on')
            ->allowedSorts([
                'title',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::partial('title'),
                AllowedFilter::exact('manuscriptAuthors.author_id'),
            ]);
    }
}
