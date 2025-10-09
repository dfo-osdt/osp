<x-mail::message>
# Hello Single Window for Science Publication Team,

*(le français suit)*

The manuscript titled "**{{ $publication->title }}**" has been accepted for publication in the following journal: {{ $publication->journal->title }}.
You are receiving this email because {{ $referrer->full_name }} has flagged this manuscript for addition to the planning binder.

<x-mail::panel>
**Title**: {{ $publication->title}}<br/>
**Journal**: {{ $publication->journal->title }}<br/>
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

# Bonjour Équipe de Guichet unique pour les publications scientifiques,

Le manuscrit intitulé "**{{ $publication->title }}**" a été accepté pour publication dans la revue suivante : {{ $publication->journal->title }}.
Vous recevez ce courriel parce que {{ $referrer->full_name }} a signalé ce manuscrit pour ajout au classeur de planification.

<x-mail::panel>
**Titre**: {{ $publication->title}}<br/>
**Revue**: {{ $publication->journal->title }}<br/>
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
