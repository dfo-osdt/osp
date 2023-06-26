<x-mail::message>
# {{ __('Hello') }} {{ $user->first_name }},

{{ __('email.auth.invitation.p1', ['name' => $invitedBy->fullName, 'email' => $invitedBy->email]) }}

{{ __('email.auth.invitation.p2') }}

{{ __('email.auth.invitation.p3') }}

<x-mail::panel>
**{{ __('Temporary Password') }}:** {{ $password }}
</x-mail::panel>

<x-mail::button :url="$url">
{{ __('Accept Invitation') }}
</x-mail::button>

<x-email.regards locale="{{ $user->locale }}" />

<x-slot:subcopy>
    {{ __('email.auth.invitation.expiry-note', ['days' => config('auth.invitation.expire_days', 5)]) }}
</x-slot:subcopy>

</x-mail::message>
