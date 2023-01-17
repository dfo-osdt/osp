<x-mail::message>
# {{ __('Hello') }} {{ $user->first_name }},

{{ __('**:name** (:email) has invited you to join the Open Science Portal to review a manuscript.', ['name' => $invitedBy->fullName, 'email' => $invitedBy->email]) }}

{{ __('Please click the button below to verify your email address and activate your account.') }}

{{ __('Use the password below to login. You will be asked to change your password on your first login.') }}

<x-mail::panel>
**{{ __('Temporary Password') }}:** {{ $password }}
</x-mail::panel>

<x-mail::button :url="$url">
{{ __('Accept Invitation') }}
</x-mail::button>

{{ __('Regards') }}, <br>
{{ __('Open Science Portal Team') }}

<x-slot:subcopy>
    {{ __('The link above will expire in 2 days. If the link has expired, you can simply register to access your acount.')}}
</x-slot:subcopy>

</x-mail::message>
