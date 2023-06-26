@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ asset('assets/logo_sm.png') }}" class="logo" alt="OSP-PSO Logo">
</div>
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
<p>This is an automated message. Please do not reply to this email. If you believe you have received this email in error, please contact the Open Science Portal team by visiting the <a href="{{config('app.frontend_url')}}">Open Science Portal</a></p>
<p>Ceci est un message automatisé. Veuillez ne pas répondre à ce courriel. Si vous croyez avoir reçu ce courriel par erreur, veuillez contacter l'équipe du Portail de la science ouverte en visitant le <a href="{{config('app.frontend_url')}}">Portail de la science ouverte</a></p>
<p>---</p>
<p>© {{ date('Y') }} Open Science Portal - Portail Science Ouverte.</p>
@endcomponent
@endslot
@endcomponent
