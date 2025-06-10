<x-mail::message>
# Hello {{ $managementReviewStep->user->first_name }},

*(le français suit)*

@if($previousStep->decision == App\Enums\ManagementReviewStepDecision::REVISION)
{{ $previousStep->user->fullName }} has flagged the manuscript titled "{{ $manuscriptRecord->title }}". Please review and address the comments below.
<p>Completion of the management review is pending your revisions, as per the comments below.</p>
<p>When ready, please click the button below to upload your revised manuscript and to restart the management review.</p>
@else
{{ $previousStep->user->fullName }} has identified you as the next management reviewer for the manuscript titled "{{ $manuscriptRecord->title }}".
@endif

<x-email.decision-expected-by locale="en" :managementReviewStep="$managementReviewStep" />

<x-mail::panel>
# Previous Review Summary
**Review Outcome:**<br />
{{ $previousStep->decision->translate('en') }}

**Comments:** <br />
{!! $previousStep->comments !!}
</x-mail::panel>

Please click on the button below or login to your [Open Science Portal]({{config('app.frontend_url')}}#/auth/login?email={{$managementReviewStep->user->email}}) account to review this manuscript record.

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$managementReviewStep->user->email.'&redirect=/manuscript/'.$managementReviewStep->manuscriptRecord->id">
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

# Bonjour {{ $managementReviewStep->user->first_name }},

@if($previousStep->decision == App\Enums\ManagementReviewStepDecision::REVISION)
{{ $previousStep->user->fullName }} a signalé des révisions nécessaires sur le manuscrit intitulé "{{ $manuscriptRecord->title }}". Veuillez examiner et traiter les commentaires ci-dessous.
<p>La révision de gestion est en attente de vos modifications, comme indiqué dans les commentaires ci-dessous.</p>
<p>Lorsque vous êtes prêt, veuillez cliquer sur le bouton ci-dessous pour télécharger votre manuscrit révisé et redémarrer la révision de gestion.</p>
@else
{{ $previousStep->user->fullName }} vous a identifié comme le prochain gestionnaire de la révision pour le manuscrit intitulé "{{ $manuscriptRecord->title }}".
@endif

<x-email.decision-expected-by locale="fr" :managementReviewStep="$managementReviewStep" />

<x-mail::panel>
# Sommaire de la révision précédente
**Décision:**<br />
{{ $previousStep->decision->translate('fr') }}

**Commentaires:** <br />
{!! $previousStep->comments !!}
</x-mail::panel>

Veuillez cliquer sur le bouton ci-dessous ou vous connecter à votre compte sur le [Portail Science Ouverte]({{config('app.frontend_url')}}#/auth/login?email={{$managementReviewStep->user->email}}) pour réviser ce registre de manuscrit.

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$managementReviewStep->user->email.'&redirect=/manuscript/'.$managementReviewStep->manuscriptRecord->id">
Réviser le manuscrit
</x-mail::button>

<x-mail::panel>
# Sommaire du manuscrit
**Titre provisoire du manuscrit:**<br /> {{ $manuscriptRecord->title }}

**Auteur(s):**<br /> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Résumé:** <br />{!! $manuscriptRecord->abstract !!}
</x-mail::panel>

<x-email.regards locale="fr" />

@if($manuscriptRecord->type === App\Enums\ManuscriptRecordType::PRIMARY && $managementReviewStep->status == App\Enums\ManagementReviewStepStatus::PENDING && $managementReviewStep->decision_expected_by !== null)
<x-email.policy-subcopy />
@endif

</x-mail::message>
