<?php

namespace App\Filament\Resources\Manuscripts;

use App\Actions\DeleteSubmittedManuscriptRecord;
use App\Enums\ManuscriptRecordStatus;
use App\Filament\Resources\Manuscripts\Pages\ListManuscripts;
use App\Filament\Resources\Manuscripts\Pages\ViewManuscripts;
use App\Filament\Resources\Manuscripts\Schemas\ManuscriptsForm;
use App\Filament\Resources\Manuscripts\Tables\ManuscriptsTable;
use App\Models\ManuscriptRecord;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
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
            'user',
        ]);
    }

    public static function form(Schema $schema): Schema
    {
        return ManuscriptsForm::configure($schema)
            ->columns(1)
            ->components(static::manuscriptFormSchema(disabled: false));
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
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->where(function (Builder $query) use ($search): void {
                        $query
                            ->where('title', 'like', "%{$search}%")
                            ->orWhereHas('user', function (Builder $query) use ($search): void {
                                $query->where('email', 'like', "%{$search}%");
                            });
                    })),
                TextColumn::make('type')
                    ->sortable(),
                TextColumn::make('status')
                    ->sortable(),
                TextColumn::make('region.slug')
                    ->sortable(),
            ])
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
                Action::make('quickView')
                    ->label('Quick View')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading('Quick View')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->extraModalFooterActions([
                        Action::make('delete')
                            ->label('Delete In-Review')
                            ->icon('heroicon-o-trash')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->visible(fn ($record): bool => $record->status === ManuscriptRecordStatus::IN_REVIEW)
                            ->cancelParentActions()
                            ->action(function ($record): void {
                                if (! $record instanceof ManuscriptRecord) {
                                    throw new \RuntimeException('Expected a ManuscriptRecord.');
                                }

                                try {
                                    DeleteSubmittedManuscriptRecord::handle($record);

                                    Notification::make()
                                        ->title('Manuscript deleted')
                                        ->success()
                                        ->send();

                                } catch (\InvalidArgumentException $e) {
                                    Notification::make()
                                        ->title('Unable to delete manuscript')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),
                    ])
                    ->form([
                        Placeholder::make('title')
                            ->label('Title')
                            ->content(fn ($record) => $record->title),

                        KeyValue::make('quick_view_data')
                            ->label('')
                            ->formatStateUsing(fn ($record): array => [
                                'Record ID' => $record->id,
                                'ULID' => $record->ulid,
                                'Type' => $record->type->value ?? $record->type,
                                'Status' => $record->status->value ?? $record->status,
                                'Owner' => $record->user->email,
                                'Region' => $record->region->name_en,
                            ])
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->action(fn (): null => null),
            ])
            ->bulkActions(
                []
            );
    }

    public static function manuscriptFormSchema(bool $disabled = false): array
    {
        return [
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

            Section::make('Title')
                ->columns(1)
                ->schema([
                    TextInput::make('title')
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManuscripts::route('/'),
            'view' => ViewManuscripts::route('/{record}'),
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
