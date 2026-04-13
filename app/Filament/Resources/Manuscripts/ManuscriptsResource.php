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
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
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
        return ManuscriptsForm::configure($schema)
            ->columns(1)
            ->components([
Section::make('Record Metadata')
            ->columns(3)
            ->schema([
                TextInput::make('id')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('ulid')
                    ->disabled()
                    ->dehydrated(false),

                Select::make('user_id')
                    ->label('Creator')
                    ->relationship('user', 'email')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('type')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('status')
                    ->disabled()
                    ->dehydrated(false),

                Select::make('region_id')
                    ->relationship('region', 'name_en')
                    ->disabled()
                    ->dehydrated(false),

                DateTimePicker::make('created_at')
                    ->disabled()
                    ->dehydrated(false),

                 DateTimePicker::make('updated_at')
                    ->disabled()
                    ->dehydrated(false),

                DateTimePicker::make('submitted_at')
                    ->disabled()
                    ->dehydrated(false),

                DateTimePicker::make('sent_for_review_at')
                    ->disabled()
                    ->dehydrated(false),

                DateTimePicker::make('reviewed_at')
                    ->disabled()
                    ->dehydrated(false),
            ]),

        Section::make('Abstract and Summaries')
            ->columns(2)
            ->schema([
                Textarea::make('abstract')
                    ->columnSpanFull()
                    ->rows(6)
                    ->disabled()
                    ->dehydrated(false),

                Textarea::make('pls_en')
                    ->label('PLS (English)')
                    ->columnSpanFull()
                    ->rows(5)
                    ->disabled()
                    ->dehydrated(false),

                Textarea::make('pls_fr')
                    ->label('PLS (French)')
                    ->columnSpanFull()
                    ->rows(5)
                    ->disabled()
                    ->dehydrated(false),
                Toggle::make('pls_approved_by_author')
                    ->label('PLS Approved by Author')
                    ->disabled()
                    ->dehydrated(false),

                Toggle::make('pls_translation_approved')
                    ->label('PLS Translation Approved')
                    ->disabled()
                    ->dehydrated(false),
            ]),

        Section::make('Audience and Public Interest')
            ->columns(1)
            ->schema([
                Textarea::make('relevant_to')
                    ->rows(4)
                    ->disabled()
                    ->dehydrated(false),

                Toggle::make('potential_public_interest')
                    ->disabled()
                    ->dehydrated(false),

                Textarea::make('public_interest_information')
                    ->rows(4)
                    ->disabled()
                    ->dehydrated(false),
            ]),

        Section::make('Open Government and Licensing')
            ->columns(2)
            ->schema([
                Toggle::make('apply_ogl')
                    ->label('Apply OGL')
                    ->disabled()
                    ->dehydrated(false),

                Toggle::make('intends_open_access')
                    ->disabled()
                    ->dehydrated(false),

                Textarea::make('no_ogl_explanation')
                    ->columnSpanFull()
                    ->rows(4)
                    ->disabled()
                    ->dehydrated(false),

                Textarea::make('open_access_rationale')
                    ->columnSpanFull()
                    ->rows(4)
                    ->disabled()
                    ->dehydrated(false),
            ]),
            ]);
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
                TrashedFilter::make(),
            ], layout: FiltersLayout::AboveContent)->filtersFormColumns(4)
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->disabled(fn ($record) => $record->trashed()),
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
