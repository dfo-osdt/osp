<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orcid\PeerReviews\Widgets;

use App\Filament\Resources\Orcid\OrcidSyncStatusStats;
use App\Filament\Resources\Orcid\PeerReviews\Pages\ListPeerReviews;
use App\Filament\Resources\Orcid\PeerReviews\PeerReviewResource;

class PeerReviewStats extends OrcidSyncStatusStats
{
    protected function getTablePage(): string
    {
        return ListPeerReviews::class;
    }

    protected function statuses(): array
    {
        return PeerReviewResource::statuses();
    }
}
