<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeFill(): void
    {
        $this->data['roles'] = $this->record->roles->pluck('name')->toArray();
    }

    /**
     * Get the URL to redirect to after performing an action in the EditUser page.
     *
     * This method overrides the default behavior and ensures that
     * after an action (such as updating a user), the user is redirected
     * to the index page of the User resource.
     *
     * @return string The URL of the index page for the User resource.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
