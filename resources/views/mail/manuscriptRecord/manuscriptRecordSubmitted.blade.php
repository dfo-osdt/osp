<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

As a Division Manager, you have received a new manuscript record submission from {{ $manuscriptRecord->user->fullname }}.

Please click on the button below or login to your [Open Science Portal]({{config('app.frontend_url')}}#/auth/login?email={{$user->email}}) account to complete the Management Review of the manuscript.

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$user->email.'&redirect=/manuscript/'.$manuscriptRecord->id">
Review Manuscript
</x-mail::button>

<x-mail::panel>
# Manuscript Summary
**Working Title:**<br /> {{ $manuscriptRecord->title }}

**Author(s):**<br /> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Abstract:** <br />{!! $manuscriptRecord->abstract !!}
</x-mail::panel>

Regards,<br />
The Open Science Team

---
<br />

# Bonjour {{ $user->first_name }},

En tant que chef de division, vous avez reçu une nouvelle soumission de registre de manuscrit de la part de {{ $manuscriptRecord->user->fullname }}.

Veuillez cliquer sur le bouton ci-dessous ou vous connecter à votre compte sur le [Portail de la science ouverte]({{config('app.frontend_url')}}#/auth/login?email={{$user->email}}) pour compléter la révision par le gestionaire.

<x-mail::button :url="config('app.frontend_url').'#/auth/login?email='.$user->email.'&redirect=/manuscript/'.$manuscriptRecord->id">
Réviser le manuscrit
</x-mail::button>

<x-mail::panel>
# Sommaire du manuscrit
**Titre provisoire du manuscrit:**<br /> {{ $manuscriptRecord->title }}

**Auteur(s):**<br /> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Résumé:** <br />{!! $manuscriptRecord->abstract !!}
</x-mail::panel>

Cordialement,<br>
L'équipe Science Ouverte



@if($manuscriptRecord->type === App\Enums\ManuscriptRecordType::PRIMARY)
<x-mail::subcopy>
Note that, as per section 1.5.3 of the [Fisheries and Oceans Canada National Policy for Science Publications](https://www.dfo-mpo.gc.ca/about-notre-sujet/publications/science/policy-politique/index-eng.html#153), science management commits to a 10 working-day turnaround for signoff of manuscripts for publication. If managers do not respond with an approval within 10 working days, authors may submit their manuscripts to the publisher.
<br/><br/>
Il convient de noter que, conformément à la section 1.5.3 de la [Politique nationale en matière de publications scientifiques de Pêches et Océans Canada](https://www.dfo-mpo.gc.ca/about-notre-sujet/publications/science/policy-politique/index-fra.html), la direction des Sciences s'engage à respecter un délai de 10 jours ouvrables pour l'approbation des manuscrits destinés à la publication. Si les gestionnaires ne donnent pas leur approbation dans les 10 jours ouvrables, les auteurs peuvent soumettre leurs manuscrits à l'éditeur.
</x-mail::subcopy>
@endif
</x-mail::message>