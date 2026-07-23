<x-mail::message>
# Hello,

*(le français suit)*

The publication titled "**{{ $publication->title }}**" has been marked as accepted for
publishing in "**{{ $publication->journal->title }}**." A new publication
record has been created in the Open Science Portal.

<x-mail::button :url="config('app.frontend_url').'#/publication/'.$publication->id">
    View Publication
</x-mail::button>

<x-email.regards locale="en" />

---

# Bonjour,

La publication intitulée "**{{ $publication->title }}**" a été marquée comme acceptée pour
publication dans "**{{ $publication->journal->title }}**." Un nouveau registre
de publication a été créé dans le Portail de la science ouverte.

<x-mail::button :url="config('app.frontend_url').'#/publication/'.$publication->id">
    Voir la publication
</x-mail::button>

<x-email.regards locale="fr" />

</x-mail::message>
