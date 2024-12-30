<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Password;

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
     * Make email_verified_at visible so the table displays the correct True/False badges.
     */
    protected function beforeFill(): void
    {
        $this->record->makeVisible(['email_verified_at']);
    }

    /**
     * Force fill the Active attribute without having to make Active castable.
     */
    protected function beforeSave(): void
    {
        if (! $this->record instanceof \App\Models\User) {
            return;
        }
        $this->record->active = $this->data['active'];
        $this->record->save();
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

    /**
     * Set the email_verified_at attribute to the current DateTime.
     */
    public function setVerifiedEmail(): void
    {
        if (! $this->record instanceof \App\Models\User) {
            return;
        }
        $this->record->markEmailAsVerified();
        $this->refreshFormData(['active']);
        Notification::make()
            ->title('User\'s email is verified!')
            ->success()
            ->send();
    }

    /**
     * Call the Reset Password Email process
     */
    public function sendPasswordReset(): void
    {
        Password::sendResetLink(['email' => $this->data['email']]);
        Notification::make()
            ->title('Reset password email sent!')
            ->success()
            ->send();
    }
}
