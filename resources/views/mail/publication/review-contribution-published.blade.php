<x-mail::message>
@php($isEditor = $contributor->role === \VincentAuger\OrcidSdk\Enums\PeerReviewRole::Editor)
# Hello {{ $user->first_name }},

*(le français suit)*

The report "**{!! \App\Services\TitleMarkup::sanitize($publication->title) !!}**", which you contributed to as {{ $isEditor ? 'an editor' : 'a reviewer' }}, has been published in "**{{ $publication->journal->title }}**."

@if ($canAddToOrcid)
You can now add this contribution to your ORCID record as a peer review.
@else
You can get credit for this contribution on your ORCID record as a peer review. Connect your ORCID iD and allow the portal to update your record, then add the contribution.
@endif

<x-mail::button :url="$url">
{{ $canAddToOrcid ? 'Add to ORCID' : 'Connect ORCID' }}
</x-mail::button>

To add future contributions automatically, turn on automatic adding on the same page.

<x-email.regards locale="en" />

---
<br />

# Bonjour {{ $user->first_name }},

Le rapport « **{!! \App\Services\TitleMarkup::sanitize($publication->title) !!}** », auquel vous avez contribué comme {{ $isEditor ? 'rédacteur' : 'évaluateur' }}, a été publié dans « **{{ $publication->journal->title }}** ».

@if ($canAddToOrcid)
Vous pouvez maintenant ajouter cette contribution à votre dossier ORCID comme évaluation par les pairs.
@else
Vous pouvez obtenir la reconnaissance de cette contribution dans votre dossier ORCID comme évaluation par les pairs. Connectez votre ORCID iD et autorisez le portail à mettre à jour votre dossier, puis ajoutez la contribution.
@endif

<x-mail::button :url="$url">
{{ $canAddToOrcid ? 'Ajouter à ORCID' : 'Connecter ORCID' }}
</x-mail::button>

Pour ajouter automatiquement vos prochaines contributions, activez l'ajout automatique sur la même page.

<x-email.regards locale="fr" />

</x-mail::message>
