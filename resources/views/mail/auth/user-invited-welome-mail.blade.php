<x-mail::message>
# {{ __('Hello') }} {{ $user->first_name }},

{{ __('email.auth.invitation.p1', ['name' => $invitedBy->fullName, 'email' => $invitedBy->email]) }}

{{ __('email.auth.invitation.p2-welcome') }}

<x-mail::button :url="$url">
{{ __('Login') }}
</x-mail::button>

<x-email.regards locale="{{ $user->locale }}" />

</x-mail::message>
