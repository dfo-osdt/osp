<x-mail::message>
# Hello {{$referrer->first_name}},

*(le français suit)*

The manuscript titled "**{{ $publication->title }}**" has been approved for publication in the series: {{ $publication->journal->title }}.
You are receiving this email because {{ $referrer->full_name }} has flagged this manuscript for addition to the planning binder.

It is the region's responsibility to include the publication in the planning binder.

The Single Window for Science Publications will hold off on publishing this manuscript until ADM approval via the planning binder. If this manuscript was flagged for the planning binder by mistake please notify DFO.OpenScience-ScienceOuverte.MPO@dfo-mpo.gc.ca

<x-mail::panel>
**Title**: {{ $publication->title}}<br/>
**Type**: {{ $state->manuscript_record_type->label('en') }}<br/>
**Region**: {{ $publication->region->name_en }}<br />
**Potential Public Interest**: {{ $publication->manuscriptRecord->potential_public_interest ? 'Yes' : 'No' }}<br/>
@if ($publication->manuscriptRecord->potential_public_interest)
**Potential Public Interest Details**: {{ $publication->manuscriptRecord->public_interest_information }}<br/>
@endif

</x-mail::panel>

<x-mail::button :url="config('app.frontend_url').'#/publication/'.$publication->id">
View Publication
</x-mail::button>

<x-email.regards locale="en" />

---

# Bonjour {{ $referrer->first_name }},

Le manuscrit intitulé "**{{ $publication->title }}**" a été accepté pour publication dans la série: {{ $publication->journal->title }}.
Vous recevez ce courriel parce que {{ $referrer->full_name }} a signalé ce manuscrit pour ajout au classeur de planification.

Il est de la responsabilité de la région d'inclure la publication dans le classeur de planification.

Le Guichet unique pour les publications scientifiques retardera la publication de ce manuscrit jusqu'à l'approbation du SMA via le classeur de planification. Si ce manuscrit a été signalé par erreur pour le classeur de planification, veuillez en informer DFO.OpenScience-ScienceOuverte.MPO@dfo-mpo.gc.ca

<x-mail::panel>
**Titre**: {{ $publication->title}}<br/>
**Type**: {{ $state->manuscript_record_type->label('fr') }}<br/>
**Région**: {{ $publication->region->name_fr }}<br/>
**Intérêt public potentiel**: {{ $publication->manuscriptRecord->potential_public_interest ? 'Oui' : 'Non' }}<br/>
@if ($publication->manuscriptRecord->potential_public_interest)
**Détails sur l'intérêt public potentiel**: {{ $publication->manuscriptRecord->public_interest_information }}<br/>
@endif

</x-mail::panel>

<x-mail::button :url="config('app.frontend_url').'#/publication/'.$publication->id">
Voir la publication
</x-mail::button>

<x-email.regards locale="fr" />

</x-mail::message>
