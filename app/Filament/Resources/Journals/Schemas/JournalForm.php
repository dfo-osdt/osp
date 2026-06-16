<?php

declare(strict_types=1);

namespace App\Filament\Resources\Journals\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JournalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('scopus_source_record_id'),
                TextInput::make('publisher')
                    ->required(),
                TextInput::make('issn'),
            ]);
    }
}
