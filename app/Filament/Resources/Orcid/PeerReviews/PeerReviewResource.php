<?php

namespace App\Filament\Resources\Orcid\PeerReviews;

use App\Enums\OrcidSyncStatus;
use App\Filament\Resources\Orcid\OrcidSyncStatusFilter;
use App\Filament\Resources\Orcid\PeerReviews\Pages\ListPeerReviews;
use App\Filament\Resources\Orcid\PeerReviews\Widgets\PeerReviewStats;
use App\Models\Journal;
use App\Models\ManuscriptReviewContributor;
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
 * Read-only audit of the review contributions in journals with ORCID sync turned
 * on, and where each stands with ORCID. Contributions still on ORCID are listed
 * until they are removed, even once their journal is turned off or they are deleted.
 */
class PeerReviewResource extends Resource
{
    #[\Override]
    protected static ?string $model = ManuscriptReviewContributor::class;

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = 'ORCID';

    #[\Override]
    protected static ?string $navigationLabel = 'Peer reviews';

    #[\Override]
    protected static ?string $modelLabel = 'peer review';

    #[\Override]
    protected static ?string $slug = 'orcid/peer-reviews';

    #[\Override]
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    /**
     * @return array<int, OrcidSyncStatus>
     */
    public static function statuses(): array
    {
        return OrcidSyncStatus::cases();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where(fn (Builder $query): Builder => $query->whereNull('deleted_at')->orWhereNotNull('orcid_putcode'))
            ->where(fn (Builder $query): Builder => $query
                ->whereNotNull('orcid_putcode')
                ->orWhereHas('manuscriptRecord.publication.journal', fn (Builder $query): Builder => $query->where('orcid_sync_enabled', true)))
            ->with(['author', 'manuscriptRecord.publication.journal']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author.full_name')
                    ->label('Author')
                    ->url(fn (ManuscriptReviewContributor $record): string => config('app.frontend_url').'#/author/'.$record->author_id, shouldOpenInNewTab: true)
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->whereHas('author', fn (Builder $query): Builder => $query
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%"))),
                TextColumn::make('author.orcid_number')
                    ->label('ORCID iD')
                    ->url(fn (ManuscriptReviewContributor $record): ?string => $record->author?->orcid, shouldOpenInNewTab: true),
                TextColumn::make('manuscriptRecord.title_plain')
                    ->label('Manuscript')
                    ->limit(60)
                    ->tooltip(fn (ManuscriptReviewContributor $record): ?string => $record->manuscriptRecord?->title_plain)
                    ->url(fn (ManuscriptReviewContributor $record): string => config('app.frontend_url').'#/manuscript/'.$record->manuscript_record_id, shouldOpenInNewTab: true),
                TextColumn::make('manuscriptRecord.publication.doi')
                    ->label('DOI'),
                TextColumn::make('manuscriptRecord.publication.journal.title')
                    ->label('Journal'),
                TextColumn::make('role')
                    ->state(fn (ManuscriptReviewContributor $record): string => $record->role->name),
                TextColumn::make('orcid_sync_status')
                    ->label('Status')
                    ->state(fn (ManuscriptReviewContributor $record): OrcidSyncStatus => $record->orcidSyncStatus())
                    ->badge()
                    ->description(fn (ManuscriptReviewContributor $record): ?string => $record->orcidSyncStatus() === OrcidSyncStatus::NOT_ELIGIBLE
                        ? __('orcid.ineligible.'.($record->orcidIneligibilityReason() ?? 'no_user_account'))
                        : null),
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
                SelectFilter::make('journal')
                    ->options(fn (): array => Journal::query()->where('orcid_sync_enabled', true)->orderBy('title')->pluck('title', 'id')->all())
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'],
                        fn (Builder $query, $journalId): Builder => $query->whereHas('manuscriptRecord.publication', fn (Builder $query): Builder => $query->where('journal_id', $journalId)),
                    )),
                OrcidSyncStatusFilter::make(self::statuses()),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2);
    }

    /**
     * Anyone with Librarium access can audit: the model policy's viewAny is
     * about authors and editors in OSP, not this read-only audit.
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
            PeerReviewStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPeerReviews::route('/'),
        ];
    }
}
