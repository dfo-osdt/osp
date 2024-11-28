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


    /**
    * Force fill the Active attribute without having to make Active castable.
    */
    protected function beforeSave(): void
    {
	$this->record->forceFill([
            'active' => $this->data['active'],
	])->save();
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
