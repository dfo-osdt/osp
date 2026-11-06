<?php

declare(strict_types=1);

namespace App\Filament\Resources\Journals\Schemas;

use App\Models\Journal;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
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
                    ->required()
                    ->live(onBlur: true),
                TextInput::make('issn'),
                Toggle::make('orcid_sync_enabled')
                    ->label('ORCID sync')
                    ->helperText('Lets authors add their review contributions to this series to their ORCID record. Review credit also needs a valid ISSN.')
                    ->visible(fn (Get $get): bool => $get('publisher') === Journal::$dfoPublisher),
            ]);
    }
}
