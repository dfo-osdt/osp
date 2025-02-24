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
     * Execute actions before applying changes to the database.
     */
    protected function beforeSave(): void
    {
        if (! $this->record instanceof \App\Models\User) {
            return;
        }

        // log email change
        if ($this->record->email != $this->data['email'] and $this->data['email']) {
            $this->logEmailChange();
        }

        // log password change
        if ($this->record->password != $this->data['password'] and $this->data['password']) {
            $this->logPasswordChange();
        }

        // Log role change
        if (count($this->record->getRoleNames()) != count($this->data['roles'])) {
            $this->logRoleChange();
        }

        // update active status
        if ($this->record['active'] != $this->data['active']) {
            $this->saveActiveStatus();
        }
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
     * Log email change and create success notification
     */
    public function logEmailChange(): void
    {
        activity('librarium')
            ->causedBy(request()->user())
            ->performedOn($this->record)
            ->withProperties([
                'subject_email' => $this->record['email'],
                'causer_email' => request()->user()->email,
                'ip' => request()->ip(),
            ])
            ->event('email.changed')
            ->log("Email changed for {$this->record['email']} by ".request()->user()->email.': '.$this->record['email'].' -> '.$this->data['email']);

        Notification::make()
            ->title('Email Changed')
            ->success()
            ->send();
    }

    /**
     * Log password change and create success notification
     */
    public function logPasswordChange(): void
    {
        activity('librarium')
            ->causedBy(request()->user())
            ->performedOn($this->record)
            ->withProperties([
                'subject_email' => $this->record['email'],
                'causer_email' => request()->user()->email,
                'ip' => request()->ip(),
            ])
            ->event('password.changed')
            ->log("Password changed for {$this->record['email']} by ".request()->user()->email);

        Notification::make()
            ->title('Password Changed')
            ->success()
            ->send();
    }

    /**
     * Log role change and create success notification
     */
    public function logRoleChange(): void
    {
        activity('librarium')
            ->causedBy(request()->user())
            ->performedOn($this->record)
            ->withProperties([
                'subject_email' => $this->record['email'],
                'causer_email' => request()->user()->email,
                'ip' => request()->ip(),
            ])
            ->event('roles.changed')
            ->log("Roles changed for {$this->record['email']} by ".request()->user()->email);

        Notification::make()
            ->title('Roles Changed')
            ->success()
            ->send();
    }

    /**
     * Save change in status to the database and log status change
     */
    public function saveActiveStatus(): void
    {
        $this->record['active'] = $this->data['active'];
        $this->record->save();

        // log active status change
        activity('librarium')
            ->causedBy(request()->user())
            ->performedOn($this->record)
            ->withProperties([
                'subject_email' => $this->record['email'],
                'causer_email' => request()->user()->email,
                'ip' => request()->ip(),
            ])
            ->event('active.status.changed')
            ->log("Active status changed for {$this->record['email']} by ".request()->user()->email.': '.(! $this->record['active'] ? 'active' : 'inactive').' -> '.($this->record['active'] ? 'active' : 'inactive'));
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

        // Update activity log
        activity('librarium')
            ->causedBy(request()->user())
            ->performedOn($this->record)
            ->withProperties([
                'subject_email' => $this->record['email'],
                'causer_email' => request()->user()->email,
                'ip' => request()->ip(),
            ])
            ->event('email.verification.set')
            ->log("Email verified for {$this->record['email']} by ".request()->user()->email.': Unverified -> Verified');
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

        // Update activity log
        activity('librarium')
            ->causedBy(request()->user())
            ->performedOn($this->record)
            ->withProperties([
                'subject_email' => $this->record['email'],
                'causer_email' => request()->user()->email,
                'ip' => request()->ip(),
            ])
            ->event('reset.password.email.sent')
            ->log("Password reset email for {$this->record['email']} sent by ".request()->user()->email.'at '.now().' UTC');
    }
}
