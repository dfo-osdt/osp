<x-mail::message>
# Hello {{ $managementReviewStep->user->first_name }},

*(le français suit)*

@if($previousStep->decision == App\Enums\ManagementReviewStepDecision::FLAGGED)
{{ $previousStep->user->fullName }} has flagged the manuscript titled "{{ $manuscriptRecord->title }}". Please review and address the comments below.
<p>When ready, please click the button below to restart the management review. Please note that you can upload a revised version of the manuscript record in the portal.</p>
@else
{{ $previousStep->user->fullName }} has identified you as the next management reviewer for the manuscript titled "{{ $manuscriptRecord->title }}".
@endif

@if($managementReviewStep->status == App\Enums\ManagementReviewStepStatus::PENDING)
A decision on this review is expected to be reached by {{ $managementReviewStep->decision_expected_by->locale('en_CA')->isoFormat('LL') }}.
@elseif($managementReviewStep->status == App\Enums\ManagementReviewStepStatus::ON_HOLD)
A decision on this review is currently on hold until the comments are addressed by the authors.
@endif

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

@if($previousStep->decision == App\Enums\ManagementReviewStepDecision::FLAGGED)
{{ $previousStep->user->fullName }} a signalé le manuscrit intitulé "{{ $manuscriptRecord->title }}". Veuillez examiner et traiter les commentaires ci-dessous.
<p>Lorsque vous êtes prêt, veuillez cliquer sur le bouton ci-dessous pour redémarrer la révision de gestion. Veuillez noter que vous pouvez télécharger une version révisée du registre de manuscrit dans le portail.</p>
@else
{{ $previousStep->user->fullName }} vous a identifié comme le prochain gestionnaire de la révision pour le manuscrit intitulé "{{ $manuscriptRecord->title }}".
@endif

@if($managementReviewStep->status == App\Enums\ManagementReviewStepStatus::PENDING)
Une décision sur cette révision est attendue d'ici le {{ $managementReviewStep->decision_expected_by->locale('fr_CA')->isoFormat('LL') }}.
@elseif($managementReviewStep->status == App\Enums\ManagementReviewStepStatus::ON_HOLD)
Une décision sur cette révision est actuellement en attente jusqu'à ce que les commentaires soient traités par les auteurs.
@endif

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

@if($manuscriptRecord->type === App\Enums\ManuscriptRecordType::PRIMARY && $managementReviewStep->status == App\Enums\ManagementReviewStepStatus::PENDING)
<x-mail::subcopy>
Note that, as per section 1.5.3 of the [Fisheries and Oceans Canada National Policy for Science Publications](https://www.dfo-mpo.gc.ca/about-notre-sujet/publications/science/policy-politique/index-eng.html#153), science management commits to a 10 working-day turnaround for signoff of manuscripts for publication. If managers do not respond with an approval within 10 working days, authors may submit their manuscripts to the publisher.
<br/><br/>
Il convient de noter que, conformément à la section 1.5.3 de la [Politique nationale en matière de publications scientifiques de Pêches et Océans Canada](https://www.dfo-mpo.gc.ca/about-notre-sujet/publications/science/policy-politique/index-fra.html), la direction des Sciences s'engage à respecter un délai de 10 jours ouvrables pour l'approbation des manuscrits destinés à la publication. Si les gestionnaires ne donnent pas leur approbation dans les 10 jours ouvrables, les auteurs peuvent soumettre leurs manuscrits à l'éditeur.
</x-mail::subcopy>
@endif

</x-mail::message>