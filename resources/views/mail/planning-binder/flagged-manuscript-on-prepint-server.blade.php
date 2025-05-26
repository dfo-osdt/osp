<x-mail::message>
# Hello Science Publication Team,

*(le français suit)*

The flagged manuscript titled "**{{ $manuscript->title }}**" has been posted on a preprint server and is now available online. Please find the details below. You can view the preprint using the button below.
You are receiving this email because {{ $referrer->full_name }} has flagged this manuscript for addition to the planning binder.

<x-mail::panel>
**Manuscript ID**: {{ $manuscript->ulid }}<br/>
**Title**: {{ $manuscript->title}}<br/>
**Region**: {{ $manuscript->region->name_en }}
**Preprint URL**: {{ $manuscript->preprint_url }}<br/>
**Potential Public Interest**: {{ $manuscript->potential_public_interest ? 'Yes' : 'No' }}<br/>
@if ($manuscript->potential_public_interest)
**Potential Public Interest Details**: {{ $manuscript->public_interest_information }}<br/>
@endif
</x-mail::panel>

<x-mail::button :url="$manuscript->preprint_url">
View Preprint
</x-mail::button>

<x-email.regards locale="en" />

---

# Bonjour Équipe de publication scientifique,

Le manuscrit signalé intitulé "**{{ $manuscript->title }}**" a été déposé sur un serveur de prépublication et est maintenant disponible en ligne. Veuillez trouver les détails ci-dessous. Vous pouvez consulter la prépublication en utilisant le bouton ci-dessous.
Vous recevez ce courriel parce que {{ $referrer->full_name }} a signalé ce manuscrit pour l’ajouter au classeur de planification.

<x-mail::panel>
**ID du manuscrit**: {{ $manuscript->ulid }}<br/>
**Titre**: {{ $manuscript->title}}<br/>
**Région**: {{ $manuscript->region->name_fr }}<br/>
**URL de prépublication**: {{ $manuscript->preprint_url }}<br/>
**Intérêt public potentiel**: {{ $manuscript->potential_public_interest ? 'Oui' : 'Non' }}<br/>
@if ($manuscript->potential_public_interest)
**Détails sur l'intérêt public potentiel** : {{ $manuscript->public_interest_information }}<br/>
@endif
</x-mail::panel>

<x-mail::button :url="$manuscript->preprint_url">
Voir la prépublication
</x-mail::button>

<x-email.regards locale="fr" />

</x-mail::message>
