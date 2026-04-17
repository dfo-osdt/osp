<?php

namespace App\Filament\Resources\Manuscripts;

use App\Actions\DeleteSubmittedManuscriptRecord;
use App\Enums\ManuscriptRecordStatus;
use App\Filament\Resources\Manuscripts\Pages\ListManuscripts;
use App\Filament\Resources\Manuscripts\Tables\ManuscriptsTable;
use App\Models\ManuscriptRecord;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
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
                            ->action(function (ManuscriptRecord $record): void {
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

    public static function getPages(): array
    {
        return [
            'index' => ListManuscripts::route('/'),
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
