<?php

namespace App\Filament\Resources\HelpfulLinks\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HelpfulLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('English')
                    ->columns(1)
                    ->schema([
                        TextInput::make('title_en')
                            ->label('Title')
                            ->required(),
                        Textarea::make('description_en')
                            ->label('Description')
                            ->required()
                            ->autosize(),
                        TextInput::make('url_en')
                            ->label('URL')
                            ->url()
                            ->nullable(),
                    ]),
                Section::make('French')
                    ->columns(1)
                    ->schema([
                        TextInput::make('title_fr')
                            ->label('Title')
                            ->required(),
                        Textarea::make('description_fr')
                            ->label('Description')
                            ->required()
                            ->autosize(),
                        TextInput::make('url_fr')
                            ->label('URL')
                            ->url()
                            ->nullable(),
                    ]),
                Section::make('Settings')
                    ->columns(2)
                    ->schema([
                        TextInput::make('order')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_visible')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}
