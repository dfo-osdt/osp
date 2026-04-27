<?php

declare(strict_types=1);

namespace App\Filament\Resources\HelpfulLinks\Pages;

use App\Filament\Resources\HelpfulLinks\HelpfulLinkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHelpfulLink extends CreateRecord
{
    protected static string $resource = HelpfulLinkResource::class;
}
