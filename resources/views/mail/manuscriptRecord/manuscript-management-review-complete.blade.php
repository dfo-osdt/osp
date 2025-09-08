<x-mail::message>
# Hello {{ $manuscriptRecord->user->first_name }},

*(le français suit)*

Your manuscript's management review is complete.


@if ($secondaryManuscript)
Please submit your manuscript titled "{{ $manuscriptRecord->title }}" to the Science publication team through the portal. 

After submission, the following steps will take place:

1. Your manuscript will be marked as accepted in the portal.
2. It will then undergo a technical edit.
3. A final proof will be sent to the corresponding author(s) for review and approval.
4. Once the proof is finalized, the publication group will arrange for your manuscript to be published in the appropriate series.

@else
You may proceed with the publication process for the manuscript titled "{{ $manuscriptRecord->title }}".

Please include “Fisheries and Oceans Canada” as your author affiliation to ensure that publications by DFO authors are retrievable in online searches and discovered for bibliometric analysis.

Once published, please ensure that you update this entry in the Open Science Portal.
@endif
If you no longer wish to publish this manuscript, please update the manuscript record status to "withdrawn".

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$manuscriptRecord->user->email.'&redirect=/manuscript/'.$manuscriptRecord->id.'/reviews'">
Update Manuscript Status
</x-mail::button>

<x-email.regards locale="en" />

---

# Bonjour {{ $manuscriptRecord->user->first_name }},

La révision par le gestionnaire de votre manuscrit est terminée.

@if($secondaryManuscript)

Veuillez soumettre votre manuscrit intitulé « {{ $manuscriptRecord->title }} » à l’équipe de publication Science via le portail.

Après la soumission, les étapes suivantes auront lieu :

1. Votre manuscrit sera marqué comme accepté dans le portail.
2. Il sera ensuite soumis à une révision technique.
3. Une épreuve finale sera envoyée au(x) auteur(s) correspondant(s) pour relecture et approbation.
4. Une fois l’épreuve finalisée, le groupe de publication organisera la publication de votre manuscrit dans la série appropriée.

@else
Vous pouvez procéder à la publication du manuscrit intitulé "{{ $manuscriptRecord->title }}".

Veuillez utiliser "Fisheries and Oceans Canada" comme affiliation de l'auteur afin de s'assurer que les publications des auteurs du MPO sont repérables dans les recherches en ligne et découvertes pour l'analyse bibliométrique.

Une fois le manuscrit publié, veuillez vous assurer de mettre à jour cette entrée dans le Portail de science ouverte.
@endif

Si vous ne souhaitez plus publier ce manuscrit, veuillez mettre à jour le statut de l'enregistrement du manuscrit à "retiré".

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$manuscriptRecord->user->email.'&redirect=/manuscript/'.$manuscriptRecord->id.'/reviews'">
Mettre à jour le statut du manuscrit
</x-mail::button>

<x-email.regards locale="fr" />

</x-mail::message>
