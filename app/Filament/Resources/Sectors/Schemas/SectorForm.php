<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sectors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SectorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name_en')
                    ->label('Name (EN)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name_fr')
                    ->label('Name (FR)')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
