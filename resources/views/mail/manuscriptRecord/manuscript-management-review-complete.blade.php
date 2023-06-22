<x-mail::message>
# Hello {{ $manuscriptRecord->user->first_name }},

Your manuscripts' management review is complete.

You may proceed with the publication process for the manuscript titled "{{ $manuscriptRecord->title }}". 

Please ensure that the text “Fisheries and Oceans Canada” appears as an author's affiliation in order to ensure that publications by DFO authors are retrievable in online searches and discovered for bibliometric analysis.

Once published, please ensure that you update the manuscript record with your publication information in the Open Science Portal.
If you decide to no longer publish this manuscript, update the manuscript record status to "withdrawn".

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$manuscriptRecord->user->email.'&redirect=/manuscript/'.$manuscriptRecord->id">
Update Manuscript Status
</x-mail::button>

Thank you,<br>
The Open Science Team

---

# Bonjour {{ $manuscriptRecord->user->first_name }},

La révision par le gestionnaire de votre manuscrit est terminée.

Vous pouvez procéder à la publication du manuscrit intitulé "{{ $manuscriptRecord->title }}".

Veuillez vous assurer que le texte "Fisheries and Oceans Canada" apparaît comme affiliation de l'auteur afin de s'assurer que les publications des auteurs du MPO sont repérables dans les recherches en ligne et découvertes pour l'analyse bibliométrique.

Une fois le manuscrit publié, veuillez tenir à jour les détails de publications de votre manuscrit dans le portail.
Si vous décidez de ne plus publier ce manuscrit, mettez à jour le statut du registre de manuscrit avec la mention "retiré".

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$manuscriptRecord->user->email.'&redirect=/manuscript/'.$manuscriptRecord->id">
Mise à jour du statut du manuscrit
</x-mail::button>

Merci,<br>
L'équipe Science Ouverte

</x-mail::message>
