<?php

declare(strict_types=1);

namespace App\Filament\Resources\Regions\Pages;

use App\Filament\Resources\Regions\Regions\RegionResource;
use App\Models\RegionNotificationGroupMember;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageRegionNotificationGroup extends ManageRelatedRecords
{
    protected static string $resource = RegionResource::class;

    protected static string $relationship = 'notificationGroupMembers';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bell';

    public static function getNavigationLabel(): string
    {
        return 'Notification Group';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back')
                ->url(fn (): string => RegionResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
            CreateAction::make()
                ->label('Add Member')
                ->model(RegionNotificationGroupMember::class)
                ->form([
                    Select::make('user_id')
                        ->label('Member')
                        ->options(fn () => User::query()
                            ->where('active', true)
                            ->get()
                            ->mapWithKeys(fn (User $user): array => [$user->id => $user->full_name]))
                        ->searchable()
                        ->required(),
                ])
                ->mutateFormDataUsing(function (array $data): array {
                    $data['region_id'] = $this->record->getKey();

                    return $data;
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.full_name')
                    ->label('Member')
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('user.email')
                    ->label('Email'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([]);
    }
}
