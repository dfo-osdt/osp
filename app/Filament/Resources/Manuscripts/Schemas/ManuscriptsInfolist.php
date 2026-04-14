<?php

declare(strict_types=1);

namespace App\Filament\Resources\Manuscripts\Schemas;

use Filament\Schemas\Schema;

class ManuscriptsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
