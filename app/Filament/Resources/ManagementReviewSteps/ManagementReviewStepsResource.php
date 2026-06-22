<?php

namespace App\Filament\Resources\ManagementReviewSteps;

use App\Actions\AdminReassignManagementReviewStep;
use App\Enums\ManagementReviewStepStatus;
use App\Filament\Resources\ManagementReviewSteps\Pages\ListManagementReviewSteps;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ManagementReviewStepsResource extends Resource
{
    protected static ?string $model = ManagementReviewStep::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Review Steps';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'manuscriptRecord']))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('manuscriptRecord.id')
                    ->label('Manuscript')
                    ->sortable(),
                TextColumn::make('user.full_name')
                    ->label('Assigned to')
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (ManagementReviewStepStatus $state): string => match ($state) {
                        ManagementReviewStepStatus::PENDING => 'warning',
                        ManagementReviewStepStatus::REASSIGN => 'gray',
                        ManagementReviewStepStatus::ON_HOLD => 'info',
                        ManagementReviewStepStatus::COMPLETED => 'success',
                    })
                    ->sortable(),
                TextColumn::make('decision_expected_by')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ManagementReviewStepStatus::class)
                    ->default(ManagementReviewStepStatus::PENDING->value),
            ])
            ->recordActions([
                Action::make('admin_reassign')
                    ->label('Reassign')
                    ->icon('heroicon-o-arrow-uturn-right')
                    ->color('warning')
                    ->visible(fn (ManagementReviewStep $record): bool => $record->status === ManagementReviewStepStatus::PENDING)
                    ->form([
                        Select::make('next_user_id')
                            ->label('Reassign to')
                            ->options(fn (ManagementReviewStep $record): array => User::query()
                                ->where('id', '!=', $record->user_id)
                                ->where('active', true)
                                ->orderBy('first_name')
                                ->get()
                                ->mapWithKeys(fn (User $user): array => [$user->id => "{$user->full_name} ({$user->email})"])
                                ->all())
                            ->searchable()
                            ->required(),
                        Textarea::make('comment')
                            ->label('Comment')
                            ->nullable(),
                    ])
                    ->action(function (ManagementReviewStep $record, array $data): void {
                        /** @var User $admin */
                        $admin = Auth::user();

                        AdminReassignManagementReviewStep::handle($record, $admin, $data['next_user_id'], $data['comment']);

                        Notification::make()
                            ->title('Step reassigned successfully')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManagementReviewSteps::route('/'),
        ];
    }
}
