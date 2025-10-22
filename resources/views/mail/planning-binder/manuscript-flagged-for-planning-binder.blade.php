<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

The manuscript titled "**{{ $manuscript->title }}**" has been flagged for
consideration in the planning binder by {{ $user->full_name }} via the
management review process. You will get another email when the publication
or preprint is created in the Open Science Portal.

It is the region’s responsibility to include the publication in the planning binder.

<x-mail::panel>
**Manuscript ID**: {{ $manuscript->ulid }}<br/>
**Title**: {{ $manuscript->title}}<br/>
**Type**: {{ $state->manuscript_record_type->label('en') }}<br/>
**Region**: {{ $manuscript->region->name_en }}
</x-mail::panel>

<x-email.regards locale="en" />

---

# Bonjour {{ $user->first_name }},

Le manuscrit intitulé "**{{ $manuscript->title }}**" a été signalé pour
examen dans le classeur de planification par {{ $user->full_name }} via le
processus d'examen de gestion. Vous recevrez un autre courriel lorsque la
publication ou prépublication sera créée dans le Portail de la science ouverte.

Il est de la responsabilité de la région d’inclure la publication dans le classeur de planification.

<x-mail::panel>
**ID du manuscrit**: {{ $manuscript->ulid }}<br/>
**Titre**: {{ $manuscript->title}}<br/>
**Type**: {{ $state->manuscript_record_type->label('fr') }}<br/>
**Région**: {{ $manuscript->region->name_fr }}
</x-mail::panel>

<x-email.regards locale="fr" />

</x-mail::message>
