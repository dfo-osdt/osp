<x-mail::message>
# Hello {{ $managementReviewStep->user->first_name }},

*(le français suit)*

{{ $previousStep->user->fullName }} has identified you as the next management reviewer for the manuscript titled "{{ $manuscriptRecord->title }}".

<x-mail::panel>
# Previous Review Summary
**Decision:**<br />
{{ $previousStep->decision->translate('en') }}

**Comments:** <br />
{!! $previousStep->comments !!}
</x-mail::panel>

Please click on the button below or login to your [Open Science Portal]({{config('app.frontend_url')}}#/auth/login?email={{$managementReviewStep->user->email}}) account to review this manuscript record.

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$managementReviewStep->user->email.'&redirect=/manuscript/'.$managementReviewStep->manuscriptRecord->id">
Review Manuscript
</x-mail::button>

<x-mail::panel>
# Manuscript Summary
**Working Title:**<br /> {{ $manuscriptRecord->title }}

**Author(s):**<br /> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Abstract:** <br />{!! $manuscriptRecord->abstract !!}
</x-mail::panel>

<x-email.regards locale="en" />

---

# Bonjour {{ $managementReviewStep->user->first_name }},

{{ $previousStep->user->fullName }} vous a identifié comme le prochain gestionnaire de la révision pour le manuscrit intitulé "{{ $manuscriptRecord->title }}".

<x-mail::panel>
# Sommaire de la révision précédente
**Décision:**<br />
{{ $previousStep->decision->translate('fr') }}

**Commentaires:** <br />
{!! $previousStep->comments !!}

</x-mail::panel>

Veuillez cliquer sur le bouton ci-dessous ou vous connecter à votre compte sur le [Portail Science Ouverte]({{config('app.frontend_url')}}#/auth/login?email={{$managementReviewStep->user->email}}) pour réviser ce registre de manuscrit.

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$managementReviewStep->user->email.'&redirect=/manuscript/'.$managementReviewStep->manuscriptRecord->id">
Réviser le manuscrit
</x-mail::button>

<x-mail::panel>
# Sommaire du manuscrit
**Titre provisoire du manuscrit:**<br /> {{ $manuscriptRecord->title }}

**Auteur(s):**<br /> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Résumé:** <br />{!! $manuscriptRecord->abstract !!}
</x-mail::panel>

<x-email.regards locale="fr" />

@if($manuscriptRecord->type === App\Enums\ManuscriptRecordType::PRIMARY)
<x-mail::subcopy>
Note that, as per section 1.5.3 of the [Fisheries and Oceans Canada National Policy for Science Publications](https://www.dfo-mpo.gc.ca/about-notre-sujet/publications/science/policy-politique/index-eng.html#153), science management commits to a 10 working-day turnaround for signoff of manuscripts for publication. If managers do not respond with an approval within 10 working days, authors may submit their manuscripts to the publisher.
<br/><br/>
Il convient de noter que, conformément à la section 1.5.3 de la [Politique nationale en matière de publications scientifiques de Pêches et Océans Canada](https://www.dfo-mpo.gc.ca/about-notre-sujet/publications/science/policy-politique/index-fra.html), la direction des Sciences s'engage à respecter un délai de 10 jours ouvrables pour l'approbation des manuscrits destinés à la publication. Si les gestionnaires ne donnent pas leur approbation dans les 10 jours ouvrables, les auteurs peuvent soumettre leurs manuscrits à l'éditeur.
</x-mail::subcopy>
@endif

</x-mail::message>