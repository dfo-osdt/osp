<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

We regret to inform you that the manuscript record titled, *{{ $manuscriptRecord->title }}*, has been withheld for publication.

For more information, please review the management review tab of your manuscript record in the Open Science Portal or contact your Division Manager.

<x-mail::button :url="config('app.frontend_url').'#/auth/login?redirect=/manuscript/'.$manuscriptRecord->id.'/reviews'">
    See Manuscript Record
</x-mail::button>

## Relevant Information from the Publication Policy
At no time, will the inclusion of sensitive material (e.g. data, scientific conclusions) prevent publication of scientific papers.

In the rare case where a manuscript does not fully adhere to policies and legislation relevant to scientific publications, the Division
Manager discusses potential revisions with the author(s). If after that discussion, the Division Manager still has concerns, he/she will identify
these concerns to the Regional Director of Science who makes the final determination. The Regional Director of Science reserves the right to withhold
permission to publish but only in cases where the publication is not in compliance with the following legislation or policy:

> The Public Servants Inventions Act, Financial Administration Act(section 34), Surplus Crown Assets Act, and with respect to collaborating authors,
> the Access to Information Act, the Copyright Act and the Privacy Act, as well as the Treasury Board Secretariat policy on Communications and Federal Identity,
> DFO policies on Intellectual Property and Collaborative Agreements, the SP and RE Collective Agreements, and the Career Progression Management Framework for
> Federal Researchers.

<x-email.regards locale="en">

---

# Bonjour {{ $user->first_name }},

Nous regrettons de vous informer que le registre de manuscrit intitulé *{{ $manuscriptRecord->title }}* a été retenu pour publication.

Pour plus d'informations, veuillez consulter l'onglet de révision de gestion de votre registre de manuscrit dans le portail de la science ou contacter votre gestionnaire de division.

<x-mail::button :url="config('app.frontend_url').'#/auth/login?redirect=/manuscript/'.$manuscriptRecord->id.'/reviews'">
    Voir le registre de manuscrit
</x-mail::button>

## Informations pertinentes de la politique de publication
À aucun moment, l'inclusion de matériel sensible (par exemple, des données, des conclusions scientifiques) n'empêchera la publication d'articles scientifiques.

Dans le cas rare où un manuscrit n'adhère pas pleinement aux politiques et à la législation relatives aux publications scientifiques, le gestionnaire de division
discute des révisions potentielles avec l'auteur(s). Si après cette discussion, le gestionnaire de division a encore des préoccupations, il/elle identifiera
ces préoccupations au directeur régional des sciences qui prend la décision finale. Le directeur régional des sciences se réserve le droit de refuser
l'autorisation de publier, mais uniquement dans les cas où la publication n'est pas conforme à la législation ou à la politique suivante:

> La Loi sur les inventions des fonctionnaires, la Loi sur la gestion des finances publiques (article 34), la Loi sur les biens excédentaires de la Couronne et,
> en ce qui concerne les auteurs collaborateurs, la Loi sur l'accès à l'information, la Loi sur le droit d'auteur et la Loi sur la protection des renseignements
> personnels, ainsi que la politique du Secrétariat du Conseil du Trésor sur les communications et l'identité fédérale, les politiques du MPO sur la propriété
> intellectuelle et les accords de collaboration, les conventions collectives SP et RE et le cadre de gestion de la progression de carrière pour les chercheurs
> fédéraux.

<x-email.regards locale="fr">

</x-mail::message>