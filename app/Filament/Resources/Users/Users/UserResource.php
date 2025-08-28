<?php

namespace App\Filament\Resources\Users\Users;

use App\Enums\Permissions\UserRole;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\Pages\ViewUserLogins;
use App\Filament\Resources\Users\Pages\ViewUserLogs;
use App\Models\User;
use App\Rules\AuthorizedEmailDomain;
use DateTime;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Contracts\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?\Filament\Pages\Enums\SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->disabledOn('edit')
                    ->filled()
                    ->required(),
                TextInput::make('last_name')
                    ->disabledOn('edit')
                    ->filled(),

                Section::make('User Information')
                    ->schema([
                        TextInput::make('email')
                            ->filled()
                            ->rules(['bail', 'required', 'string', 'email', new AuthorizedEmailDomain])
                            ->disabled(fn () => config('osp.azure.enable_auth', false)),
                        CheckboxList::make('roles')
                            ->relationship(titleAttribute: 'name')
                            ->getOptionLabelFromRecordUsing(fn (Role $record) => UserRole::from($record->name)->label())
                            ->label('Roles'),
                    ]),
                Section::make('Change Password')
                    ->schema([
                        TextInput::make('password')
                            ->label('Change Password')
                            ->password()
                            ->rules([Password::min(config('auth.password_min_length'))->uncompromised()])
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state)),
                    ]),

                Section::make('Available Actions')
                    ->description('Actions are instantaneous and may disappear once applied!')
                    ->schema([
                        Toggle::make('active')
                            ->label('Activate User')
                            ->dehydrated(false)
                            ->hidden(fn ($record) => $record && ! $record->email_verified_at)
                            ->onColor('success'),
                        Actions::make([
                            Action::make('reset_password')
                                ->label('Send Password Reset Email')
                                ->disabled(fn () => config('osp.azure.enable_auth', false) || ! request()->routeIs('filament.librarium.resources.users.edit'))
                                ->action('sendPasswordReset'),
                            Action::make('verify_email')
                                ->label('Activate User & Verify Email')
                                ->disabled(fn ($record) => isset($record->email_verified_at) || ! request()->routeIs('filament.librarium.resources.users.edit'))
                                ->action('setVerifiedEmail'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->default('heroicon-o-x-circle')
                    ->icon(fn ($state) => $state instanceof DateTime ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state instanceof DateTime ? 'success' : 'danger')
                    ->sortable(),
                IconColumn::make('active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => UserRole::from($state)->label())
                    ->color(fn (string $state): string => match (UserRole::from($state)) {
                        UserRole::AUTHOR => 'success',
                        UserRole::DIRECTOR, UserRole::EDITOR, UserRole::CHIEF_EDITOR => 'warning',
                        UserRole::ADMIN => 'danger',
                        UserRole::NFL_EDITOR, UserRole::MAR_EDITOR, UserRole::GLF_EDITOR, 
                        UserRole::QUE_EDITOR, UserRole::ONP_EDITOR, UserRole::ARC_EDITOR, 
                        UserRole::PAC_EDITOR, UserRole::NCR_EDITOR => 'info',
                    })
                    ->searchable(),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Verified')
                    ->nullable()
                    ->placeholder('All'),
                SelectFilter::make('active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Role $record) => UserRole::from($record->name)->label())
                    ->label('Role'),
                Filter::make('no_roles')
                    ->label('No Roles')
                    ->query(fn ($query) => $query->whereDoesntHave('roles')),
            ], layout: FiltersLayout::AboveContent)->filtersFormColumns(4)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('first_name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'view' => ViewUser::route('/{record}'),
            'log' => ViewUserLogs::route('/{record}/log'),
            'logins' => ViewUserLogins::route('/{record}/login'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewUser::class,
            EditUser::class,
            ViewUserLogs::class,
            ViewUserLogins::class,
        ]);
    }
}
