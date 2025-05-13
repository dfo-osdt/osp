<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

In accordance with the DFO publication policy, you have received a new manuscript record submission from {{ $manuscriptRecord->user->fullname }}.

Please click on the button below or login to your [Open Science Portal]({{config('app.frontend_url')}}#/auth/login?email={{$user->email}}) account to complete the Management Review of the manuscript.

<x-email.decision-expected-by locale="en" :managementReviewStep="$manuscriptRecord->managementReviewSteps->first()" />

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$user->email.'&redirect=/manuscript/'.$manuscriptRecord->id">
Review Manuscript
</x-mail::button>

<x-mail::panel>
# Manuscript Summary
**Working Title:**<br /> {{ $manuscriptRecord->title }}

**Author(s):**<br /> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Abstract:** <br />{!! $manuscriptRecord->abstract !!}
</x-mail::panel>

<x-email.regards locale="en" />

---
<br />

# Bonjour {{ $user->first_name }},

Conformément à la politique de publication du MPO, vous avez reçu une nouvelle soumission de registre de manuscrit de la part de {{ $manuscriptRecord->user->fullname }}.

Veuillez cliquer sur le bouton ci-dessous ou vous connecter à votre compte sur le [Portail Science Ouverte]({{config('app.frontend_url')}}#/auth/login?email={{$user->email}}) pour compléter la révision par le gestionaire.

<x-email.decision-expected-by locale="fr" :managementReviewStep="$manuscriptRecord->managementReviewSteps->first()" />

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$user->email.'&redirect=/manuscript/'.$manuscriptRecord->id">
Réviser le manuscrit
</x-mail::button>

<x-mail::panel>
# Sommaire du manuscrit
**Titre provisoire du manuscrit:**<br /> {{ $manuscriptRecord->title }}

**Auteur(s):**<br /> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Résumé:** <br />{!! $manuscriptRecord->abstract !!}
</x-mail::panel>

<x-email.regards locale="fr" />


@if($manuscriptRecord->type === App\Enums\ManuscriptRecordType::PRIMARY)
<x-email.policy-subcopy />
@endif
</x-mail::message>