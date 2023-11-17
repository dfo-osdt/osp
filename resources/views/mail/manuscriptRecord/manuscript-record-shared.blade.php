<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

{{ $sharedBy->fullname }} has shared a manuscript record with you.

Please click on the button below or login to your [Open Science Portal]({{config('app.frontend_url')}}#/auth/login?email={{$user->email}}) account to view the manuscript.

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$user->email.'&redirect=/manuscript/'.$manuscriptRecord->id">
View Manuscript
</x-mail::button>

<x-mail::panel>
# Manuscript Summary
**Working Title:**<br /> {{ $manuscriptRecord->title }} <br/>
**Share Type:**<br /> {{ $can_edit ? 'Edit' : 'Read only' }} <br/>
**Expiration Date:**<br /> {{ $expires_at ?? 'Never' }} <br/>
</x-mail::panel>

<x-email.regards locale="en" />

---
<br />

# Bonjour {{ $user->first_name }},

{{ $sharedBy->fullname }} a partagé un registre de manuscrit avec vous.

Veuillez cliquer sur le bouton ci-dessous ou vous connecter à votre compte sur le [Portail Science Ouverte]({{config('app.frontend_url')}}#/auth/login?email={{$user->email}}) pour voir le manuscrit.

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$user->email.'&redirect=/manuscript/'.$manuscriptRecord->id">
Visioner le manuscrit
</x-mail::button>

<x-mail::panel>
# Sommaire du manuscrit
**Titre provisoire du manuscrit:**<br /> {{ $manuscriptRecord->title }} <br/>
**Type de partage:**<br /> {{ $can_edit ? 'Modifier' : 'Lecture seule' }} <br/>
**Date d'expiration:**<br /> {{ $expires_at ?? 'Jamais' }} <br/>
</x-mail::panel>

<x-email.regards locale="fr" />

</x-mail::message>