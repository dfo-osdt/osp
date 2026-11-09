<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orcid\PeerReviews\Pages;

use App\Filament\Resources\Orcid\PeerReviews\PeerReviewResource;
use App\Filament\Resources\Orcid\PeerReviews\Widgets\PeerReviewStats;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListPeerReviews extends ListRecords
{
    use ExposesTableToWidgets;

    #[\Override]
    protected static string $resource = PeerReviewResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            PeerReviewStats::class,
        ];
    }
}
