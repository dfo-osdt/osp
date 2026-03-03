<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\Users\UserResource;
use App\Models\ManagementReviewDelegation;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageUserDelegations extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'managementReviewDelegations';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    public static function getNavigationLabel(): string
    {
        return 'Delegations';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back')
                ->url(fn (): string => UserResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
            CreateAction::make()
                ->label('Create Delegation')
                ->model(ManagementReviewDelegation::class)
                ->form([
                    Select::make('delegate_user_id')
                        ->label('Delegate')
                        ->options(fn () => User::query()
                            ->where('id', '!=', $this->record->getKey())
                            ->where('active', true)
                            ->get()
                            ->mapWithKeys(fn (User $user): array => [$user->id => $user->full_name]))
                        ->searchable()
                        ->required(),
                    DateTimePicker::make('starts_at')
                        ->label('Starts At')
                        ->nullable(),
                    DateTimePicker::make('ends_at')
                        ->label('Ends At')
                        ->required()
                        ->after('starts_at'),
                    Textarea::make('comment')
                        ->label('Comment')
                        ->nullable(),
                ])
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = $this->record->getKey();

                    return $data;
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('delegate.full_name')
                    ->label('Delegate')
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ended_early_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('—'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->state(fn (ManagementReviewDelegation $record): bool => $record->isActive())
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('end_early')
                    ->label('End Early')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (ManagementReviewDelegation $record): bool => $record->isActive())
                    ->action(fn (ManagementReviewDelegation $record) => $record->update(['ended_early_at' => now()])),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([]);
    }
}
