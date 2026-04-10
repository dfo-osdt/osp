<?php

namespace App\Filament\Resources\Manuscripts;

use App\Filament\Resources\Manuscripts\Pages\EditManuscripts;
use App\Filament\Resources\Manuscripts\Pages\ListManuscripts;
use App\Filament\Resources\Manuscripts\Pages\ViewManuscripts;
use App\Filament\Resources\Manuscripts\Schemas\ManuscriptsForm;
use App\Filament\Resources\Manuscripts\Schemas\ManuscriptsInfolist;
use App\Filament\Resources\Manuscripts\Tables\ManuscriptsTable;
use App\Models\ManuscriptRecord;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManuscriptsResource extends Resource
{
    protected static ?string $model = ManuscriptRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'managementReviewSteps',
            'manuscriptAuthors.author',
            'shareables',
            'region',
        ]);
    }

    public static function form(Schema $schema): Schema
    {
        return ManuscriptsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ManuscriptsInfolist::configure($schema)
            ->components([
                Section::make()
                    ->schema([
                        KeyValueEntry::make('attributes')
                            ->label('Log Entry (JSON)')
                            ->default(fn ($record) => $record->getAttributes()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return ManuscriptsTable::configure($table)
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->sortable(),
                TextColumn::make('status')
                    ->sortable(),
                TextColumn::make('Region.slug')
                    ->sortable(),
            ])->searchable(['email'])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'primary' => 'Primary',
                        'secondary' => 'Secondary',
                        'preprint' => 'Pre-Print',
                    ]),
                SelectFilter::make('region')
                    ->relationship('region', 'slug'),
            ], layout: FiltersLayout::AboveContent)->filtersFormColumns(4)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions(
                []
            );
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManuscripts::route('/'),
            'view' => ViewManuscripts::route('/{record}'),
            'edit' => EditManuscripts::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
