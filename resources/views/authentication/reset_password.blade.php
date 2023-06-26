<x-mail::message>
{{ __('Hello') }} {{ $user->first_name }},

{{__('email.auth.reset.p1')}}

<x-mail::button :url="$url">
{{__('email.auth.reset.action')}}
</x-mail::button>

{{__('email.auth.reset.p2',[ 'minutes' => config('auth.passwords.users.expire')])}}

{{__('email.auth.reset.p3')}}

<x-email.regards :locale="app()->getLocale()" />
</x-mail::message>
