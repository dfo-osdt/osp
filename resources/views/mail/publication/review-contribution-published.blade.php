<x-mail::message>
@php($isEditor = $contributor->role === \VincentAuger\OrcidSdk\Enums\PeerReviewRole::Editor)
# Hello {{ $user->first_name }},

*(le français suit)*

@if ($canAddToOrcid)
A report you contributed to has been published. You can now get credit for it by adding it to your ORCID record as a peer review.
@else
A report you contributed to has been published. You can get credit for it on your ORCID record as a peer review.
@endif

<x-mail::panel>
**Report:**<br /> {!! \App\Services\TitleMarkup::sanitize($publication->title) !!} <br/>
**Series:**<br /> {{ $publication->journal->title }} <br/>
**Your role:**<br /> {{ $isEditor ? 'Editor' : 'Reviewer' }} <br/>
</x-mail::panel>

@if ($canAddToOrcid)
Open your ORCID review credit page and select **Add** next to this contribution.
@else
To add it, open your ORCID review credit page, connect your ORCID iD and allow the portal to update your record, then select **Add** next to this contribution.
@endif

<x-mail::button :url="$url">
Open ORCID review credit
</x-mail::button>

@if ($canAddToOrcid)
To have future contributions added for you, turn on **Add new contributions automatically** on that page.
@else
Once connected, you can also turn on **Add new contributions automatically** so future contributions are added for you.
@endif

<x-email.regards locale="en" />

---
<br />

# Bonjour {{ $user->first_name }},

@if ($canAddToOrcid)
Un rapport auquel vous avez contribué a été publié. Vous pouvez maintenant en obtenir la reconnaissance en l'ajoutant à votre dossier ORCID comme évaluation par les pairs.
@else
Un rapport auquel vous avez contribué a été publié. Vous pouvez en obtenir la reconnaissance dans votre dossier ORCID comme évaluation par les pairs.
@endif

<x-mail::panel>
**Rapport :**<br /> {!! \App\Services\TitleMarkup::sanitize($publication->title) !!} <br/>
**Série :**<br /> {{ $publication->journal->title }} <br/>
**Votre rôle :**<br /> {{ $isEditor ? 'Rédacteur' : 'Évaluateur' }} <br/>
</x-mail::panel>

@if ($canAddToOrcid)
Ouvrez votre page Reconnaissance ORCID des évaluations et sélectionnez **Ajouter** à côté de cette contribution.
@else
Pour l'ajouter, ouvrez votre page Reconnaissance ORCID des évaluations, connectez votre ORCID iD et autorisez le portail à mettre à jour votre dossier, puis sélectionnez **Ajouter** à côté de cette contribution.
@endif

<x-mail::button :url="$url">
Ouvrir la reconnaissance ORCID des évaluations
</x-mail::button>

@if ($canAddToOrcid)
Pour que vos prochaines contributions soient ajoutées pour vous, activez **Ajouter automatiquement les nouvelles contributions** sur cette page.
@else
Une fois votre ORCID connecté, vous pourrez aussi activer **Ajouter automatiquement les nouvelles contributions** pour que vos prochaines contributions soient ajoutées pour vous.
@endif

<x-email.regards locale="fr" />

</x-mail::message>
