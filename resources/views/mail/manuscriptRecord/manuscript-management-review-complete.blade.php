<x-mail::message>
# Hello {{ $manuscriptRecord->user->first_name }},

*(le français suit)*

Your manuscript's management review is complete.

You may proceed with the publication process for the manuscript titled "{{ $manuscriptRecord->title }}".

Please include “Fisheries and Oceans Canada” as your author affiliation to ensure that publications by DFO authors are retrievable in online searches and discovered for bibliometric analysis.

Once published, please ensure that you update this entry in the Open Science Portal.
If you no longer wish to publish this manuscript, please update the manuscript record status to "withdrawn".

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$manuscriptRecord->user->email.'&redirect=/manuscript/'.$manuscriptRecord->id.'/reviews'">
Update Manuscript Status
</x-mail::button>

<x-email.regards locale="en" />

---

# Bonjour {{ $manuscriptRecord->user->first_name }},

La révision par le gestionnaire de votre manuscrit est terminée.

Vous pouvez procéder à la publication du manuscrit intitulé "{{ $manuscriptRecord->title }}".

Veuillez utiliser "Fisheries and Oceans Canada" comme affiliation de l'auteur afin de s'assurer que les publications des auteurs du MPO sont repérables dans les recherches en ligne et découvertes pour l'analyse bibliométrique.

Une fois le manuscrit publié, veuillez vous assurer de mettre à jour cette entrée dans le Portail de science ouverte.
Si vous ne souhaitez plus publier ce manuscrit, veuillez mettre à jour le statut de l'enregistrement du manuscrit à "retiré".

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$manuscriptRecord->user->email.'&redirect=/manuscript/'.$manuscriptRecord->id.'/reviews'">
Mettre à jour le statut du manuscrit
</x-mail::button>

<x-email.regards locale="fr" />

</x-mail::message>
