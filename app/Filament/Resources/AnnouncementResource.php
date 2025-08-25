<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use App\Filament\Resources\AnnouncementResource\Pages\ListAnnouncements;
use App\Filament\Resources\AnnouncementResource\Pages\CreateAnnouncement;
use App\Filament\Resources\AnnouncementResource\Pages\EditAnnouncement;
use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Infolists\Components;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bell';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Announcement Content')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title_en')
                            ->label('Title English')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('title_fr')
                            ->label('Title French')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('text_en')
                            ->label('Announcement English')
                            ->autosize()
                            ->required()
                            ->maxLength(500),
                        Textarea::make('text_fr')
                            ->label('Announcement French')
                            ->autosize()
                            ->required()
                            ->maxLength(500),
                    ]),
                Section::make('Dates')
                    ->description('Announcements times are in UTC')
                    ->schema([
                        DateTimePicker::make('start_at')
                            ->label('Start Date')
                            ->default(fn () => now())
                            ->required()
                            ->before('end_at'),
                        DateTimePicker::make('end_at')
                            ->label('End Date')
                            ->default(fn () => now()->addDay()->endOfDay())
                            ->required()
                            ->after('start_at'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title_en')
                    ->label('Title English')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('title_fr')
                    ->label('Title French')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created (UTC)')
                    ->datetime('M d, Y H:i'),
                TextColumn::make('start_at')
                    ->label('Start (UTC)')
                    ->datetime('M d, Y H:i')
                    ->sortable(),
                TextColumn::make('end_at')
                    ->label('End (UTC)')
                    ->datetime('M d, Y H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(true)
                    ->label('Status')
                    ->getStateUsing(function (Announcement $record) {
                        $now = Carbon::now();
                        if ($record->start_at > $now) {
                            return 'Upcoming';
                        }
                        if ($record->start_at <= $now && $record->end_at >= $now) {
                            return 'Active';
                        }
                        if ($record->end_at < $now) {
                            return 'Passed';
                        }
                    })
                    ->colors([
                        'warning' => 'Upcoming',
                        'success' => 'Active',
                        'info' => 'Passed',
                    ]),
            ])
            ->defaultSort('start_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Info')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Group::make([
                                    TextEntry::make('title_en')
                                        ->label('Title English'),
                                    TextEntry::make('created_at')
                                        ->label('Created (UTC)')
                                        ->datetime('M d, Y H:i'),
                                    TextEntry::make('start_at')
                                        ->label('Start (UTC)')
                                        ->datetime('M d, Y H:i'),
                                ]),
                                Group::make([
                                    TextEntry::make('title_fr')
                                        ->label('Title French'),
                                    TextEntry::make('status')
                                        ->badge()
                                        ->getStateUsing(function ($record) {
                                            $now = Carbon::now();
                                            if ($record->start_at > $now) {
                                                return 'Upcoming';
                                            }
                                            if ($record->start_at <= $now && $record->end_at >= $now) {
                                                return 'Active';
                                            }
                                            if ($record->end_at < $now) {
                                                return 'Passed';
                                            }
                                        })
                                        ->colors([
                                            'secondary' => 'Upcoming',
                                            'success' => 'Active',
                                            'danger' => 'Passed',
                                        ]),
                                    TextEntry::make('end_at')
                                        ->label('End (UTC)')
                                        ->datetime('M d, Y H:i'),
                                ])
                                    ->columns(1),
                            ]),
                    ]),
                Section::make('Message')
                    ->schema([
                        TextEntry::make('text_en')
                            ->label('English'),
                        TextEntry::make('text_fr')
                            ->label('French'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAnnouncements::route('/'),
            'create' => CreateAnnouncement::route('/create'),
            'edit' => EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
