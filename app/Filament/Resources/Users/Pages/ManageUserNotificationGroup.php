<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\Users\UserResource;
use App\Models\NotificationGroupMember;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageUserNotificationGroup extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

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
                ->url(fn (): string => UserResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
            CreateAction::make()
                ->label('Add Member')
                ->model(NotificationGroupMember::class)
                ->form([
                    Select::make('member_user_id')
                        ->label('Member')
                        ->options(fn () => User::query()
                            ->where('id', '!=', $this->record->getKey())
                            ->where('active', true)
                            ->get()
                            ->mapWithKeys(fn (User $user): array => [$user->id => $user->full_name]))
                        ->searchable()
                        ->required(),
                    DateTimePicker::make('expires_at')
                        ->label('Expires At')
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
                TextColumn::make('member.full_name')
                    ->label('Member')
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('member.email')
                    ->label('Email'),
                TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Never'),
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
