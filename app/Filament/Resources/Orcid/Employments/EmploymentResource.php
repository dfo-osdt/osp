<?php

namespace App\Filament\Resources\Orcid\Employments;

use App\Enums\OrcidSyncStatus;
use App\Filament\Resources\Orcid\Employments\Pages\ListEmployments;
use App\Filament\Resources\Orcid\Employments\Widgets\EmploymentStats;
use App\Filament\Resources\Orcid\OrcidSyncStatusFilter;
use App\Models\AuthorEmployment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * Read-only audit of author employments and where each stands with ORCID.
 * Deleted employments are listed until they are removed from ORCID.
 */
class EmploymentResource extends Resource
{
    #[\Override]
    protected static ?string $model = AuthorEmployment::class;

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = 'ORCID';

    #[\Override]
    protected static ?string $navigationLabel = 'Employments';

    #[\Override]
    protected static ?string $modelLabel = 'employment';

    #[\Override]
    protected static ?string $slug = 'orcid/employments';

    #[\Override]
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    /**
     * @return array<int, OrcidSyncStatus>
     */
    public static function statuses(): array
    {
        return [
            OrcidSyncStatus::NOT_CONNECTED,
            OrcidSyncStatus::PENDING,
            OrcidSyncStatus::ON_ORCID,
            OrcidSyncStatus::REMOVING,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where(fn (Builder $query): Builder => $query->whereNull('deleted_at')->orWhereNotNull('orcid_putcode'))
            ->with(['author', 'organization']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author.full_name')
                    ->label('Author')
                    ->url(fn (AuthorEmployment $record): string => config('app.frontend_url').'#/author/'.$record->author_id, shouldOpenInNewTab: true)
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->whereHas('author', fn (Builder $query): Builder => $query
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%"))),
                TextColumn::make('author.orcid_number')
                    ->label('ORCID iD')
                    ->url(fn (AuthorEmployment $record): ?string => $record->author?->orcid, shouldOpenInNewTab: true),
                TextColumn::make('organization.name_en')
                    ->label('Organization'),
                TextColumn::make('role_title')
                    ->label('Role'),
                TextColumn::make('start_date')
                    ->date(),
                TextColumn::make('end_date')
                    ->date(),
                TextColumn::make('orcid_sync_status')
                    ->label('Status')
                    ->state(fn (AuthorEmployment $record): OrcidSyncStatus => $record->orcidSyncStatus())
                    ->badge(),
                TextColumn::make('orcid_putcode')
                    ->label('Put-code'),
                TextColumn::make('orcid_updated_at')
                    ->label('Last synced')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('organization')
                    ->relationship('organization', 'name_en')
                    ->searchable(),
                OrcidSyncStatusFilter::make(self::statuses()),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2);
    }

    /**
     * Anyone with Librarium access can audit: the model policy's viewAny is
     * about an author's own employments in OSP, not this read-only audit.
     */
    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getWidgets(): array
    {
        return [
            EmploymentStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployments::route('/'),
        ];
    }
}
