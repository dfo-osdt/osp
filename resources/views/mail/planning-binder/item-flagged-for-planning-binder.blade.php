<x-mail::message>
# Hello Science Publication Team,

*(le français suit)*

@if($manuscript)
The manuscript titled "**{{ $manuscript->title }}**" has been flagged for
consideration in the planning binder by {{ $user->full_name }} via the
management review process. You will get another email when the publication
is created in the Open Science Portal.
@else
The publication titled "**{{ $publication->title }}**" has been flagged for
consideration in the planning binder by {{ $user->full_name }} via the
management review process.
@endif

<x-email.regards locale="en" />

---

# Bonjour Équipe de publication,

@if($manuscript)
Le manuscrit intitulé "**{{ $manuscript->title }}**" a été signalé pour
examen dans le classeur de planification par {{ $user->full_name }} via le
processus d'examen de gestion. Vous recevrez un autre courriel lorsque la
publication sera créée dans le Portail de la science ouverte.
@else
La publication intitulée "**{{ $publication->title }}**" a été signalée pour
examen dans le classeur de planification par {{ $user->full_name }} via le
processus d'examen de gestion.
@endif

<x-email.regards locale="fr" />

</x-mail::message>
