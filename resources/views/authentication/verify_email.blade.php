<x-mail::message>
{{__('email.auth.verify.greeting')}}, {{ $user }}!

{{__('email.auth.verify.p1')}}

<x-mail::button :url="$url">
{{__('email.auth.verify.action')}}
</x-mail::button>

{{__('email.auth.verify.expiry-note', ['minutes' => config('auth.verification.expire')])}}

{{__('email.auth.verify.p2')}}

<x-email.regards :locale="app()->getLocale()"/>
</x-mail::message>
