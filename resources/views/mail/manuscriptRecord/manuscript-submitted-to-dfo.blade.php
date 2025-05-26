<x-mail::message>
# Hello Science Publication Team,

*(le français suit)*

The manuscript titled "**{{ $publication->title }}**" has been submitted for
publishing in "**{{ $publication->journal->title }}**." A new publication record
has been created in the Open Science Portal and can be found at the link
below.

<x-mail::button :url="config('app.frontend_url').'#/publication/'.$publication->id">
    View Publication
</x-mail::button>

Please note that only a Chief Editor can mark the publication as published.

<x-email.regards locale="en" />

---

# Bonjour Équipe de publication,

Le manuscrit intitulé "**{{ $publication->title }}**" a été soumis pour
publication dans "**{{$publication->journal->title}}**." Un nouveau registre de
publication a été créé dans le Portail de la science ouverte et peut être
consulté au lien ci-dessous.

<x-mail::button :url="config('app.frontend_url').'#/publication/'.$publication->id">
    Voir la publication
</x-mail::button>

Veuillez noter que seul un rédacteur en chef peut marquer la publication
comme publiée.

<x-email.regards locale="fr" />

</x-mail::message>
