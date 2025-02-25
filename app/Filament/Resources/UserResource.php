<?php

namespace App\Filament\Resources;

use App\Enums\Permissions\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Rules\AuthorizedEmailDomain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Contracts\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->disabledOn('edit')
                    ->filled()
                    ->required(),
                Forms\Components\TextInput::make('last_name')
                    ->disabledOn('edit')
                    ->filled(),

                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->filled()
                            ->rules(['bail', 'required', 'string', 'email', new AuthorizedEmailDomain])
                            ->disabled(fn () => config('osp.azure.enable_auth', false)),
                        Forms\Components\CheckboxList::make('roles')
                            ->relationship(titleAttribute: 'name')
                            ->getOptionLabelFromRecordUsing(fn (Role $record) => UserRole::from($record->name)->label())
                            ->label('Roles'),
                    ]),
                Forms\Components\Section::make('Change Password')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Change Password')
                            ->password()
                            ->rules([Password::min(config('auth.password_min_length'))->uncompromised()])
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state)),
                    ]),

                Forms\Components\Section::make('Available Actions')
                    ->description('Actions are instantaneous and may disappear once applied!')
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->label('Activate User')
                            ->dehydrated(false)
                            ->hidden(fn ($record) => $record && ! $record->email_verified_at)
                            ->onColor('success'),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('reset_password')
                                ->label('Send Password Reset Email')
                                ->disabled(fn () => config('osp.azure.enable_auth', false) || ! request()->routeIs('filament.librarium.resources.users.edit'))
                                ->action('sendPasswordReset'),
                            Forms\Components\Actions\Action::make('verify_email')
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
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->default('heroicon-o-x-circle')
                    ->icon(fn ($state) => $state instanceof \DateTime ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state instanceof \DateTime ? 'success' : 'danger')
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => UserRole::from($state)->label())
                    ->color(fn (string $state): string => match (UserRole::from($state)) {
                        UserRole::AUTHOR => 'success',
                        UserRole::DIRECTOR, UserRole::EDITOR, UserRole::CHIEF_EDITOR => 'warning',
                        UserRole::ADMIN => 'danger',
                    })
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Verified')
                    ->nullable()
                    ->placeholder('All'),
                Tables\Filters\SelectFilter::make('active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
                Tables\Filters\Filter::make('no_roles')
                    ->label('No Roles')
                    ->query(fn ($query) => $query->whereDoesntHave('roles')),
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Role $record) => UserRole::from($record->name)->label())
                    ->label('Role'),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Placeholder
                ]),
            ])
            ->defaultSort('first_name');
    }

    public static function getRelations(): array
    {
        return [
            // ...
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}'),
            'log' => Pages\LogUser::route('/{record}/log'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewUser::class,
            Pages\EditUser::class,
            Pages\LogUser::class,
        ]);
    }

    public static function getWidgets(): array
    {
        return [
            UserResource\Widgets\ActivityLogView::class,
        ];
    }
}
