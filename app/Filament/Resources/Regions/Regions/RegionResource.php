<?php

declare(strict_types=1);

namespace App\Filament\Resources\Regions\Regions;

use App\Filament\Resources\Regions\Pages\EditRegion;
use App\Filament\Resources\Regions\Pages\ListRegions;
use App\Models\Region;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-americas';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Region Settings')
                    ->schema([
                        Toggle::make('enforce_secondary_review_deadline')
                            ->label('Enforce Review Deadline for Secondary (DFO) Publications')
                            ->helperText('When enabled, secondary publications in this region will receive a '.config('osp.management_review.decision_expected_business_days').'-business-day review deadline with due/overdue reminders.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_en')
                    ->label('Name (EN)')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name_fr')
                    ->label('Name (FR)')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Slug'),
                IconColumn::make('enforce_secondary_review_deadline')
                    ->label('Secondary Review Deadline')
                    ->boolean(),
            ])
            ->defaultSort('name_en');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Region Details')
                    ->schema([
                        TextEntry::make('name_en')
                            ->label('Name (EN)'),
                        TextEntry::make('name_fr')
                            ->label('Name (FR)'),
                        TextEntry::make('slug')
                            ->label('Slug'),
                        IconEntry::make('enforce_secondary_review_deadline')
                            ->label('Enforce Secondary Review Deadline')
                            ->boolean(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegions::route('/'),
            'edit' => EditRegion::route('/{record}/edit'),
        ];
    }
}
