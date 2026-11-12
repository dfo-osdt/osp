<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

@if ($canAddToOrcid)
A report you authored has been published. You can now add it to your ORCID record as a work.
@else
A report you authored has been published. It can be added to your ORCID record as a work.
@endif

<x-mail::panel>
**Report:**<br /> {!! \App\Services\TitleMarkup::sanitize($publication->title) !!} <br/>
**Series:**<br /> {{ $publication->journal->title }} <br/>
</x-mail::panel>

@if ($canAddToOrcid)
Open your ORCID works page and select **Add** next to this publication.
@else
To add it, open your ORCID works page, connect your ORCID iD and allow the portal to update your record, then select **Add** next to this publication.
@endif

<x-mail::button :url="$url">
Open ORCID works
</x-mail::button>

@if ($canAddToOrcid)
To have future publications added for you, turn on **Add new publications automatically** on that page.
@else
Once connected, you can also turn on **Add new publications automatically** so future publications are added for you.
@endif

<x-email.regards locale="en" />

---
<br />

# Bonjour {{ $user->first_name }},

@if ($canAddToOrcid)
Un rapport dont vous êtes l'auteur a été publié. Vous pouvez maintenant l'ajouter à votre dossier ORCID comme travail.
@else
Un rapport dont vous êtes l'auteur a été publié. Il peut être ajouté à votre dossier ORCID comme travail.
@endif

<x-mail::panel>
**Rapport :**<br /> {!! \App\Services\TitleMarkup::sanitize($publication->title) !!} <br/>
**Série :**<br /> {{ $publication->journal->title }} <br/>
</x-mail::panel>

@if ($canAddToOrcid)
Ouvrez votre page Travaux ORCID et sélectionnez **Ajouter** à côté de cette publication.
@else
Pour l'ajouter, ouvrez votre page Travaux ORCID, connectez votre ORCID iD et autorisez le portail à mettre à jour votre dossier, puis sélectionnez **Ajouter** à côté de cette publication.
@endif

<x-mail::button :url="$url">
Ouvrir les travaux ORCID
</x-mail::button>

@if ($canAddToOrcid)
Pour que vos prochaines publications soient ajoutées pour vous, activez **Ajouter automatiquement les nouvelles publications** sur cette page.
@else
Une fois votre ORCID connecté, vous pourrez aussi activer **Ajouter automatiquement les nouvelles publications** pour que vos prochaines publications soient ajoutées pour vous.
@endif

<x-email.regards locale="fr" />

</x-mail::message>
