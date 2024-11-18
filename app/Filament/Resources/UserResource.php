<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Rules\AuthorizeEmailDomain;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->disabledOn('edit')
                    ->Filled()
                    ->required(),
                Forms\Components\TextInput::make('last_name')
					  ->disabledOn('edit')
					  ->Filled(),
                Forms\Components\Section::make([
		    Forms\Components\TextInput::make('email')
					      ->Filled()
					      ->rules(['required', 'string', 'email', new AuthorizeEmailDomain()]),
		    Forms\Components\Actions::make([
			Forms\Components\Actions\Action::make('email_verified_at')
						       ->action(function (Forms\Get $get, Forms\Set $set) {
							   $set('email_verified_at', false);
						       }
						       )
			]
							   ),

		    Forms\Components\CheckboxList::make('roles')
						 ->relationship(titleAttribute: 'name')
						 ->default(['1'])
						 ->options([
						     '3' => 'Admin',
						     '2' => 'Director',
						 ]),
		]),
	    ]);
    }

    public static function table(Table $table): Table
    {
	return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'author' => 'success',
                        'director' => 'warning',
                        'admin' => 'danger',
                    })
                    ->searchable(isIndividual: true),
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
		Tables\Filters\Filter::make('author')
				     ->label('Author')
				     ->query(fn ($query) => $query->whereHas('roles', fn ($query) => $query->where('name', 'author'))),

		Tables\Filters\Filter::make('director')
				     ->label('Director')
				     ->query(fn ($query) => $query->whereHas('roles', fn ($query) => $query->where('name', 'director'))),

		Tables\Filters\Filter::make('admin')
				     ->label('Admin')
				     ->query(fn ($query) => $query->whereHas('roles', fn ($query) => $query->where('name', 'admin'))),

	    ])
	    ->actions([
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
	    //
	];
    }

    public static function getPages(): array
    {
	//	$user = Filament::auth()->user();
	//	Gate::authorize('updateAnyUser', $user);

	return [
	    'index' => Pages\ListUsers::route('/'),
	    'create' => Pages\CreateUser::route('/create'),
	    'edit' => Pages\EditUser::route('/{record}/edit'),
	];
    }
}
